<?php

namespace App\Http\Controllers;

use App\Models\CustomerInfo;
use App\Models\Attachment;
use App\Models\AttachmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerInfoController extends Controller
{
    // List all customers
    public function index()
    {
        $customers = CustomerInfo::with('attachments.type')->get();
        return response()->json($customers);
    }

    // Store a new customer with attachments
    public function store(Request $request)
    {
       // dd($request->all());
        $request->validate([
            'CustomerCode' => 'required|unique:customer_infos,CustomerCode',
            'CustomerName' => 'required|string',
            'attachments.*.file' => 'nullable|file|max:5120',
            'attachments.*.type_id' => 'required_with:attachments.*.file|exists:attachment_types,AttachmentTypeID'
        ]);

        DB::beginTransaction();

        try {
            $customer = CustomerInfo::create([
                'CustomerCode' => $request->CustomerCode,
                'CustomerName' => $request->CustomerName,
                'Business' => $request->Business,
                'FatherName' => $request->FatherName,
                'Address' => $request->Address,
                'Contact' => $request->Contact,
                'NID' => $request->NID,
                'downPayment' => $request->downPayment,
                'FinanceAmount' => $request->FinanceAmount,
                'OutstandingAmount' => $request->OutstandingAmount,
                'MaturedAmount' => $request->MaturedAmount,
                'NonMaturedAmount' => $request->NonMaturedAmount,
                'OverDueTaka' => $request->OverDueTaka,
                'NoOfInstallment' => $request->NoOfInstallment,
                'InvoiceDate' => $request->InvoiceDate,
                'TTYCode' => $request->TTYCode,
                'TTYName' => $request->TTYName,
                'BoxNo' => $request->BoxNo,
                'CreatedBy' => auth()->user()->name ?? 'system',
                'CreatedAt' => now()
            ]);

            // Handle attachments
            if ($request->has('attachments')) {
                foreach ($request->attachments as $attach) {
                    if (isset($attach['file'])) {
                        $path = $attach['file']->store('customer_attachments', 'public');

                        Attachment::create([
                            'CustomerInfoID' => $customer->CustomerInfoID,
                            'AttachmentTypeID' => $attach['type_id'],
                            'AttachmentRaw' => $path,
                            'AttachmentOriginal' => $attach['file']->getClientOriginalName(),
                            'CreatedBy' => auth()->user()->name ?? 'system',
                            'CreatedAt' => now()
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Customer created successfully', 'customer' => $customer], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Show customer details
    public function show($id)
    {
        $customer = CustomerInfo::with('attachments.type')->findOrFail($id);
        return response()->json($customer);
    }

    // Update customer info
    public function update(Request $request, $id)
    {
        $customer = CustomerInfo::findOrFail($id);

        $request->validate([
            'CustomerCode' => 'required|unique:customer_infos,CustomerCode,' . $id . ',CustomerInfoID',
            'CustomerName' => 'required|string'
        ]);

        $customer->update($request->only([
            'CustomerCode', 'CustomerName', 'Business', 'FatherName', 'Address', 'Contact',
            'NID', 'downPayment', 'FinanceAmount', 'OutstandingAmount', 'MaturedAmount', 'NonMaturedAmount', 'InvoiceDate', 'TTYCode', 'TTYName', 'BoxNo'
        ]));

        return response()->json(['message' => 'Customer updated successfully', 'customer' => $customer]);
    }

    // public function updateAttachments(Request $request)
    // {
    //     $request->validate([
    //         'attachments' => 'nullable|array',
    //         'attachments.*.type_id' => 'required',
    //         'attachments.*.file' => 'nullable',
    //         'CustomerInfoID' => 'required',
    //     ]);

    //     $CustomerInfoID = $request->input('CustomerInfoID');

    //     if (!$request->attachments || count($request->attachments) == 0) {
    //         return response()->json([
    //             'message' => 'No attachments provided',
    //         ]);
    //     }

    //     foreach ($request->attachments as $att) {

    //         // Compatible with validation: attachments.*.type_id
    //         $AttachmentTypeID = $att['type_id'];

    //         $file = $att['file'];

    //         // Check previous record (MUST match both CustomerInfoID + AttachmentTypeID)
    //         $existing = Attachment::where('CustomerInfoID', $CustomerInfoID)
    //             ->where('AttachmentTypeID', $AttachmentTypeID)
    //             ->first();

    //         // If file is not uploaded (URL string), keep existing and skip upload
    //         if (!($file instanceof \Illuminate\Http\UploadedFile)) {

    //             if (!$existing) {
    //                 // create record using URL
    //                 Attachment::create([
    //                     'CustomerInfoID' => $CustomerInfoID,
    //                     'AttachmentTypeID' => $AttachmentTypeID,
    //                     'AttachmentRaw' => $file,
    //                     'AttachmentOriginal' => null,
    //                 ]);
    //             }

    //             continue;
    //         }

    //         // Upload new file
    //         $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
    //         $filePath = $file->storeAs('customer_attachments', $fileName, 'public');

    //         $AttachmentRaw = asset('storage/' . $filePath);
    //         $AttachmentOriginal = $file->getClientOriginalName();

    //         // If previous file exists → delete & update
    //         if ($existing) {

    //             if ($existing->AttachmentRaw) {
    //                 $oldFile = str_replace(asset('storage/') . '/', '', $existing->AttachmentRaw);
    //                 Storage::disk('public')->delete($oldFile);
    //             }

    //             $existing->update([
    //                 'AttachmentRaw' => $AttachmentRaw,
    //                 'AttachmentOriginal' => $AttachmentOriginal,
    //             ]);

    //         } else {
    //             // insert new record
    //             Attachment::create([
    //                 'CustomerInfoID' => $CustomerInfoID,
    //                 'AttachmentTypeID' => $AttachmentTypeID,
    //                 'AttachmentRaw' => $AttachmentRaw,
    //                 'AttachmentOriginal' => $AttachmentOriginal,
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'message' => 'Attachments updated successfully',
    //     ]);
    // }


    public function updateAttachments(Request $request)
        {
            $request->validate([
                'CustomerInfoID' => 'required',
                'BoxNo' => 'required',
                'attachments' => 'nullable|array',
                'attachments.*.type_id' => 'required|integer',
                'attachments.*.file' => 'nullable|file',
                'attachments.*.file_url' => 'nullable|string',
            ]);

            DB::beginTransaction();

            try {
                $customer = CustomerInfo::findOrFail($request->CustomerInfoID);

                $updateData = [
                    'BoxNo' => $request->BoxNo,
                ];

                $customer->update($updateData);

                $attachments = $request->attachments ?? [];

                foreach ($attachments as $attach) {
                    if (!empty($attach['file_url'])) {
                        continue;
                    }
                    if (!isset($attach['file']) || !$attach['file']) {
                        continue;
                    }

                    $file = $attach['file'];

                    $path = $file->store('customer_attachments', 'public');

                    Attachment::create([
                        'CustomerInfoID' => $customer->CustomerInfoID,
                        'AttachmentTypeID' => $attach['type_id'],
                        'AttachmentRaw' => $path,
                        'AttachmentOriginal' => $file->getClientOriginalName(),
                        'CreatedBy' => auth()->user()->name ?? 'system',
                        'CreatedAt' => now(),
                    ]);
                }

                DB::commit();
                return response()->json(['message' => 'Attachments updated successfully'], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                ], 500);
            }
        }




    // Delete customer
    public function destroy($id)
    {
        $customer = CustomerInfo::findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }

    public function business_wise_customer_code(Request $request)
    {
        $business = $request->query('business');
        $search = $request->query('search');

        if ($business == 'foton') {
            $query = "SELECT CustomerCode, CustomerName1
                    FROM Customer
                    WHERE Business='F' AND Active='Y'";

            if (!empty($search)) {
                $query .= " AND CustomerName1 LIKE ?";
                $customerCodes = DB::connection('sqlsrv2')->select($query, ["%{$search}%"]);
            } else {
                $customerCodes = DB::connection('sqlsrv2')->select($query);
            }

        } elseif ($business == 'tractor') {
            $query = "SELECT CustomerCode, CustomerName1
                    FROM Customer
                    WHERE Active='Y' AND Business='Q'";

            if (!empty($search)) {
                $query .= " AND CustomerName1 LIKE ?";
                $customerCodes = DB::connection('sqlsrv3')->select($query, ["%{$search}%"]);
            } else {
                $customerCodes = DB::connection('sqlsrv3')->select($query);
            }
        } else {
            return response()->json([
                'message' => 'Invalid business type!',
                'data' => []
            ], 400);
        }

        return response()->json([
            'message' => 'Customer codes retrieved successfully!',
            'data' => $customerCodes
        ], 200);
    }

    public function business_wise_territory_code(Request $request)
    {
        $business = $request->query('business');
        $search = $request->query('search');

        $query = DB::table('customer_infos')
            ->select('TTYCode', 'TTYName');

        // Apply business filter only if value exists
        if (!empty($business)) {
            $query->where('Business', $business);
        }

        // Search by TTYCode OR TTYName
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('TTYCode', 'LIKE', "%{$search}%")
                ->orWhere('TTYName', 'LIKE', "%{$search}%");
            });
        }

        $result = $query
            ->groupBy('TTYCode', 'TTYName')
            ->orderBy('TTYCode', 'asc')
            ->get();

        return response()->json([
            'message' => 'Territory codes retrieved successfully!',
            'data' => $result
        ], 200);
    }


        public function customer_wise_territory_code(Request $request)
        {
            $customer = $request->query('customer');
            $search   = $request->query('search');

            $query = DB::table('customer_infos')
                ->select('TTYCode', 'TTYName');

            if (!empty($customer)) {
                $query->where('CustomerCode', $customer);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('TTYCode', 'LIKE', "%{$search}%")
                    ->orWhere('TTYName', 'LIKE', "%{$search}%");
                });
            }

            $data = $query
                ->groupBy('TTYCode', 'TTYName')
                ->orderBy('TTYCode', 'asc')
                ->get();

            return response()->json([
                'message' => 'Territory codes retrieved successfully!',
                'data'    => $data
            ]);
        }


    public function customer_code_wise_allinfo_pdf(Request $request)
    {
        $customercode = $request->query('customercode');

        if (!$customercode) {
            return response()->json([
                'message' => 'Customer code is required'
            ], 422);
        }

        $rows = DB::table('customer_infos')
            ->leftJoin('attachments', 'customer_infos.CustomerInfoID', '=', 'attachments.CustomerInfoID')
            ->leftJoin('attachment_types', 'attachment_types.AttachmentTypeID', '=', 'attachments.AttachmentTypeID')
            ->where('customer_infos.CustomerCode', $customercode)
            ->select('customer_infos.*', 'attachments.AttachmentRaw', 'attachment_types.AttachmentType')
            ->orderByRaw("
                CASE attachment_types.AttachmentType
                    WHEN 'Customer Photo' THEN 1
                    WHEN 'Guarantor Photo' THEN 2
                    WHEN 'Customer Signature' THEN 3
                    WHEN 'Guarantor Signature' THEN 4
                    WHEN 'Customer NID' THEN 5
                    WHEN 'Guarantor NID' THEN 6
                    WHEN 'Bio Data' THEN 7
                    WHEN 'Trade License' THEN 8
                    WHEN 'Agreement' THEN 9
                    WHEN 'Signature Verification' THEN 10
                    WHEN 'Cheque' THEN 11
                    ELSE 99
                END
            ")
            ->get();

            //dd($rows);

        if ($rows->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        $customer    = $rows->first();
        $attachments = $rows->groupBy('AttachmentType');

        $pdf = Pdf::loadView('pdf.customer_profile', compact('customer', 'attachments'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,  // Page number এর জন্য
            ]);

        return $pdf->download("Customer_Profile_{$customercode}.pdf");
    }




    public function customer_search(Request $request)
    {
        $search = $request->query('search');

        $query = DB::table('customer_infos')
            ->select('CustomerCode', 'CustomerName');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('CustomerCode', 'LIKE', "%{$search}%")
                ->orWhere('CustomerName', 'LIKE', "%{$search}%");
            });
        }
        $result = $query
            ->groupBy('CustomerCode', 'CustomerName')
            ->orderBy('CustomerCode', 'asc')
            ->get();

        return response()->json([
            'message' => 'Customer list retrieved successfully!',
            'data' => $result
        ], 200);
    }


    public function files_list(Request $request)
    {
        $result = DB::table('attachment_types')->get();

        return response()->json([
            'message' => 'Customer list retrieved successfully!',
            'data' => $result
        ], 200);
    }

    public function business_wise_customer_info(Request $request)
    {
        $business  = $request->query('business');
        $territory = $request->query('territory');
        $filetype  = $request->query('filetype');
        $code      = $request->query('code');

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Fetch Customers by Filter
        |--------------------------------------------------------------------------
        */
        $customerQuery = DB::table('customer_infos');

        if (!empty($business)) {
            $customerQuery->where('Business', $business);
        }

        if (!empty($territory)) {
            $customerQuery->where('TTYCode', $territory);
        }

        if (!empty($code)) {
            $customerQuery->where('CustomerCode', $code);
        }

        $customers = $customerQuery->get();

        if ($customers->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No customer found with the given filters.'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ Collect Customer IDs
        |--------------------------------------------------------------------------
        */
        $customerIDs = $customers->pluck('CustomerInfoID')->toArray();

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ Fetch Attachments (Filter by File Type)
        |--------------------------------------------------------------------------
        */
        $attachmentsQuery = Attachment::whereIn('CustomerInfoID', $customerIDs);

        if (!empty($filetype)) {
            $attachmentsQuery->where('AttachmentTypeID', $filetype);
        }

        $attachments = $attachmentsQuery->get();

        if ($attachments->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No attachments found for the selected customers.'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ Create ZIP File
        |--------------------------------------------------------------------------
        */
        $zipFileName = 'Customers_Attachments_' . now()->format('Ymd_His') . '.zip';
        $zipPath     = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Could not create ZIP file.'
            ], 500);
        }

        foreach ($attachments as $att) {

            if (!$att->AttachmentRaw) {
                continue;
            }

            if (!Storage::disk('public')->exists($att->AttachmentRaw)) {
                continue;
            }

            $filePath = storage_path('app/public/' . $att->AttachmentRaw);

            // ✅ ORIGINAL FILE NAME
            $originalName = $att->AttachmentOriginal
                ?? basename($att->AttachmentRaw);

            // ✅ Prevent duplicate names inside ZIP
            $filenameInZip = $att->AttachmentID . '_' . $originalName;

            $zip->addFile($filePath, $filenameInZip);
        }

        $zip->close();

        /*
        |--------------------------------------------------------------------------
        | 5️⃣ Download ZIP & Auto Delete
        |--------------------------------------------------------------------------
        */
        return response()
            ->download($zipPath, $zipFileName)
            ->deleteFileAfterSend(true);
    }


    // public function business_wise_customer_info(Request $request)
    // {
    //     $business  = $request->query('business');
    //     $territory = $request->query('territory');
    //     $filetype  = $request->query('filetype');
    //     $code      = $request->query('code');

    //     // Fetch customer(s) matching filters
    //     $customerQuery = DB::table('customer_infos');

    //     if (!empty($business)) {
    //         $customerQuery->where('Business', $business);
    //     }

    //     if (!empty($territory)) {
    //         $customerQuery->where('TTYCode', $territory);
    //     }

    //     if (!empty($code)) {
    //         $customerQuery->where('CustomerCode', $code);
    //     }

    //     $customers = $customerQuery->get();

    //     if ($customers->isEmpty()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'No customer found with the given filters.'
    //         ], 404);
    //     }

    //     // Collect all CustomerInfoIDs
    //     $customerIDs = $customers->pluck('CustomerInfoID')->toArray();

    //     // Fetch attachments for these customers, filter by filetype if provided
    //     $attachmentsQuery = Attachment::whereIn('CustomerInfoID', $customerIDs);



    //     if (!empty($filetype)) {
    //         $attachmentsQuery->where('AttachmentTypeID', $filetype);
    //     }

    //     $attachments = $attachmentsQuery->get();

    //     if ($attachments->isEmpty()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'No attachments found for the selected customers.'
    //         ], 404);
    //     }

    //     // Create a ZIP file
    //     $zipFileName = "Customers_Attachments.zip";
    //     $zipPath = storage_path('app/public/' . $zipFileName);

    //     $zip = new ZipArchive;
    //     if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    //         foreach ($attachments as $att) {
    //             if ($att->AttachmentRaw && Storage::disk('public')->exists($att->AttachmentRaw)) {
    //                 $filePath = storage_path('app/public/' . $att->AttachmentRaw);

    //                 // Use original attachment name or fallback to filename
    //                 $filenameInZip = ($att->CustomerInfoID ?? 'Customer') . '_' . ($att->AttachmentRaw ?? basename($filePath));
    //                 $zip->addFile($filePath, $filenameInZip);
    //             }
    //         }
    //         $zip->close();
    //     } else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Could not create ZIP file.'
    //         ], 500);
    //     }

    //     // Return ZIP file as download and delete after sending
    //     return response()->download($zipPath)->deleteFileAfterSend(true);
    // }





    // public function business_wise_customer_show(Request $request)
    // {
    //     $business = $request->query('business');
    //     $customercode = $request->query('customercode');
    //     $currentDate = now()->format('Y-m-d');

    //     // First try from local DB
    //     $customerCodes = CustomerInfo::with('attachments.type')
    //         ->where('CustomerCode', $customercode)
    //         ->get();

    //     // If not found locally, check business-wise external DB
    //     if ($customerCodes->isEmpty()) {

    //         if ($business == 'foton') {

    //             $query = "sp_CustomerStatus '%', 'Jan 1 2025', '$currentDate', 'RPT', 'N','0', 'F', '?','%'";

    //             $customerCodes = DB::connection('sqlsrv2')->select($query, [$customercode]);

    //         } elseif ($business == 'tractor') {

    //             $query = "sp_CustomerStatus '%', 'Jan 1 2025', '$currentDate', 'RPT', 'N','0', 'Q', '?','%'";

    //             $customerCodes = DB::connection('sqlsrv3')->select($query, [$customercode]);

    //         } else {
    //             return response()->json([
    //                 'message' => 'Invalid business type.',
    //                 'data' => [],
    //             ], 400);
    //         }
    //     }

    //     // Final empty check (array or collection safe)
    //     if (!$customerCodes || count($customerCodes) === 0) {
    //         return response()->json([
    //             'message' => 'No customer found for the given code.',
    //             'data' => [],
    //         ], 404);
    //     }

    //     // Attachment handling
    //     $customerinfoids = CustomerInfo::where(
    //         'CustomerCode',
    //         $customerCodes[0]->CustomerCode
    //     )->get();

    //     foreach ($customerinfoids as $customerCode) {

    //         $CustomerInfoID = (string) $customerCode->CustomerInfoID;

    //         $attachments = Attachment::where(
    //             'CustomerInfoID',
    //             $CustomerInfoID
    //         )->get();

    //         foreach ($attachments as $attachment) {
    //             $attachment->AttachmentRaw = url('storage/' . $attachment->AttachmentRaw);
    //         }

    //         $customerCode->attachments = $attachments;
    //     }

    //     return response()->json([
    //         'message' => 'Customer information successfully retrieved!',
    //         'data' => $customerCodes,
    //     ], 200);
    // }
public function business_wise_customer_show(Request $request)
{
    $business = $request->query('business');
    $customercode = $request->query('customercode');
    $currentDate = now()->format('M j Y');

    // First try from local DB
    $customerCodes = CustomerInfo::with('attachments.type')
        ->where('CustomerCode', $customercode)
        ->get();

    // Found in local DB - return as array
    if ($customerCodes->isNotEmpty()) {
        foreach ($customerCodes as $customerCode) {
            foreach ($customerCode->attachments as $attachment) {
                $attachment->AttachmentRaw = url('storage/' . $attachment->AttachmentRaw);
            }
        }

        return response()->json([
            'message' => 'Customer information successfully retrieved!',
            'data' => $customerCodes,
        ], 200);
    }

    // Not found locally, check business-wise external DB
    if ($business == 'foton') {
        $connection = 'sqlsrv2';
        $query = "EXEC sp_CustomerStatus '%', 'Jan 1 2025', '$currentDate', 'RPT', 'N','0', 'F', '$customercode','%'";

        $query1 = "SELECT C.FatherName1 AS FatherName, C.Address1 AS Address, C.NID, C.Business AS BusinessName, CAST(ps.ScheduleDate AS DATE) AS InvoiceDate
                FROM Customer C
                LEFT JOIN PaymentSchedule ps
                    ON ps.CustomerCode = C.CustomerCode
                INNER JOIN Business b
                    ON b.Business = C.Business
                WHERE C.Business = 'F' AND C.Active = 'Y' AND C.CustomerCode = ?";

        $customerCodes = DB::connection('sqlsrv2')->select($query1, [$customercode]);

    } elseif ($business == 'tractor') {
        $connection = 'sqlsrv3';
        $query = "EXEC sp_CustomerStatus '%', 'Jan 1 2025', '$currentDate', 'RPT', 'N','0', 'Q', '$customercode','%'";

        $query1 = "SELECT C.FatherName1 AS FatherName, C.Address1 AS Address, C.NID, C.Business AS BusinessName, CAST(ps.ScheduleDate AS DATE) AS InvoiceDate
                FROM Customer C
                LEFT JOIN PaymentSchedule ps
                    ON ps.CustomerCode = C.CustomerCode
                INNER JOIN Business b
                    ON b.Business = C.Business
                WHERE C.Business = 'Q' AND C.Active = 'Y' AND C.CustomerCode = ?";

        $customerCodes = DB::connection('sqlsrv3')->select($query1, [$customercode]);

    } else {
        return response()->json([
            'message' => 'Invalid business type.',
            'data' => [],
        ], 400);
    }

    $pdo = DB::connection($connection)->getPdo();
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // First result set - financial summary
    $financialSummary = [];
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $financialSummary[] = $row;
    }

    // Second result set - product/booking details
    $stmt->nextRowset();
    $bookingDetails = [];
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $bookingDetails[] = $row;
    }

    if (empty($financialSummary) && empty($bookingDetails)) {
        return response()->json([
            'message' => 'No customer found for the given code.',
            'data' => [],
        ], 404);
    }

    // Attachment handling from local DB
    $customerinfoids = CustomerInfo::where('CustomerCode', $customercode)->get();

    $attachmentsData = [];
    foreach ($customerinfoids as $customerInfo) {
        $attachments = Attachment::with('type')
            ->where('CustomerInfoID', $customerInfo->CustomerInfoID)
            ->get();

        foreach ($attachments as $attachment) {
            $attachment->AttachmentRaw = url('storage/' . $attachment->AttachmentRaw);
            $attachmentsData[] = [
                'AttachmentID' => $attachment->AttachmentID,
                'CustomerInfoID' => (string) $attachment->CustomerInfoID,
                'AttachmentTypeID' => (string) $attachment->AttachmentTypeID,
                'AttachmentRaw' => $attachment->AttachmentRaw,
                'AttachmentOriginal' => $attachment->AttachmentOriginal,
                'CreatedBy' => $attachment->CreatedBy,
                'CreatedAt' => $attachment->CreatedAt,
                'type' => $attachment->type ? [
                    'AttachmentTypeID' => $attachment->type->AttachmentTypeID,
                    'AttachmentType' => $attachment->type->AttachmentType,
                    'Active' => $attachment->type->Active,
                    'created_at' => $attachment->type->created_at,
                    'updated_at' => $attachment->type->updated_at,
                ] : null,
            ];
        }
    }

    // Map SP fields to local DB structure
    $mappedData = [];
    if (!empty($financialSummary)) {
        $firstRecord = $financialSummary[0];

        $mappedData = [
            'CustomerInfoID' => null,
            'CustomerCode' => $firstRecord['CustomerCode'] ?? null,
            'CustomerName' => $firstRecord['CustomerName1'] ?? null,
            'Business' => $customerCodes[0]->BusinessName ?? null,
            'FatherName' => $customerCodes[0]->FatherName ?? null,
            'Address' => $customerCodes[0]->Address ?? null,
            'Contact' => $firstRecord['Mobile'] ?? null,
            'NID' => $customerCodes[0]->NID ?? null,
            'downPayment' => $firstRecord['DownPayment'] ?? null,
            'FinanceAmount' => $firstRecord['PrincipalAmount'] ?? null,
            'OutstandingAmount' => $firstRecord['TotalOutstanding'] ?? null,
            'MaturedAmount' => $firstRecord['DueAmount'] ?? null,
            'NonMaturedAmount' => $firstRecord['TotalReturnAmount'] - $firstRecord['DueAmount'] ?? null,
            'OverDueTaka' => $firstRecord['OverDueTaka'] ?? null,
            'NoOfInstallment' => $firstRecord['NoOfInstallment'] ?? null,
            'DownPayment' => $firstRecord['DownPayment'] ?? null,
            'InvoiceDate' => $customerCodes[0]->InvoiceDate ?? null,
            'TTYCode' => $firstRecord['TTYCode'] ?? null,
            'TTYName' => $firstRecord['TTYname'] ?? null,
            'BoxNo' => null,
            'attachments' => $attachmentsData,
        ];
    }

    return response()->json([
        'message' => 'Customer information successfully retrieved!',
        'data' => [$mappedData],
    ], 200);
}



    public function attachment_types()
    {
        $types = AttachmentType::all();
        return response()->json($types);
    }


   public function removeAttachment(Request $request)
    {
        $request->validate([
            'AttachmentID' => 'required|integer',
        ]);

        try {
            $attachment = Attachment::findOrFail($request->AttachmentID);

            // Delete file from storage if exists
            if ($attachment->AttachmentRaw && Storage::disk('public')->exists($attachment->AttachmentRaw)) {
                Storage::disk('public')->delete($attachment->AttachmentRaw);
            }

            // Delete database record
            $attachment->delete();

            return response()->json([
                'message' => 'Attachment removed successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove attachment',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getAttachmentsByCustomer($CustomerInfoID)
    {
        try {
        $attachments = Attachment::where('CustomerInfoID', $CustomerInfoID)->get();

        if ($attachments->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No attachments found for this customer.'
            ], 404);
        }

        $zipFileName = "Customer_{$CustomerInfoID}_Attachments.zip";
        $zipPath = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($attachments as $att) {
                if ($att->AttachmentRaw && Storage::disk('public')->exists($att->AttachmentRaw)) {
                    $filePath = storage_path('app/public/' . $att->AttachmentRaw);
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();
        } else {
            throw new \Exception("Could not create ZIP file.");
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }



}
