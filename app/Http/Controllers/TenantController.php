<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantDocument;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class TenantController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage tenant')) {
            $tenants = Tenant::where('parent_id',parentId())->get();
            return view('tenant.index', compact('tenants'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create tenant')) {
            $property = Property::where('parent_id',parentId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);
            return view('tenant.create', compact('property'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function store(Request $request)
    {

            if (\Auth::user()->can('create tenant')) {
            $validator = \Validator::make(
                $request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'phone_number' => 'required',
                'title' => 'required',
                'business_name' => 'required',
                'business_number' => 'required',
                'tax_payer_identification' => 'required',
                'contact_information' => 'required',
                'company_name' => 'required',
                'property' => 'required',
                'unit' => 'required',
                'lease_start_date' => 'required',
                'lease_end_date' => 'required',
            ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),
                ]);

            }
            $ids = parentId();
            $authUser = \App\Models\User::find($ids);
            $totalTenant = $authUser->totalTenant();
            $subscription = Subscription::find($authUser->subscription);
            if ($totalTenant >= $subscription->tenant_limit && $subscription->tenant_limit != 0) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('Your tenant limit is over, please upgrade your subscription.'),
                    'id' => 0,
                ]);
            }

            $userRole = Role::where('parent_id',parentId())->where('name','tenant')->first();

            $user=new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = \Hash::make($request->password);
            $user->phone_number = $request->phone_number;
            $user->type = $userRole->name;
            $user->profile = 'avatar.png';
            $user->lang = 'english';
            $user->parent_id =parentId();
            $user->save();
            $user->assignRole($userRole);

            // if ($request->profile!='undefined') {
            //     $tenantFilenameWithExt = $request->file('profile')->getClientOriginalName();
            //     $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
            //     $tenantExtension = $request->file('profile')->getClientOriginalExtension();
            //     $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
            //     $dir = storage_path('upload/profile');
            //     if (!file_exists($dir)) {
            //         mkdir($dir, 0777, true);
            //     }
            //     $request->file('profile')->storeAs('upload/profile/', $tenantFileName);
            //     $user->profile = $tenantFileName;
            //     $user->save();
            // }

            $tenant = new Tenant();
            $tenant->user_id = $user->id;
            $tenant->title = $request->title;
            $tenant->business_name = $request->business_name;
            $tenant->business_number = $request->business_number;
            $tenant->tax_payer_identification = $request->tax_payer_identification;
            $tenant->contact_information = $request->contact_information;
            $tenant->address = $request->company_name;
            $tenant->property = $request->property;
            $tenant->unit = $request->unit;
            $tenant->lease_start_date = $request->lease_start_date;
            $tenant->lease_end_date = $request->lease_end_date;
            $tenant->parent_id =parentId();
            $tenant->save();


            $tenantImage = new TenantDocument();
            $tenantImage->property_id = $request->property;
            $tenantImage->tenant_id = $tenant->id;


            // if (!empty($request->bank_statement)) {
                // foreach ($request->bank_statement as $file) {
                    // $tenantFilenameWithExt = $request->bank_statement->getClientOriginalName();
                    // $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                    // $tenantExtension = $request->bank_statement->getClientOriginalExtension();
                    // $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
                    // // $dir = storage_path('upload/tenant');
                    // $dir = storage_path('app/public/upload/tenant'); // Corrected path for storage
                    // if (!file_exists($dir)) {
                    //     mkdir($dir, 0777, true);
                    // }
                    // // $file->storeAs('upload/tenant/', $tenantFileName);
                    // $file->storeAs('public/upload/tenant/', $tenantFileName); // Corrected storage path



                    $tenantImage->document = $request->contract;
                // }
            // }



            // // if (!empty($request->contract)) {
            //     foreach ($request->contract as $file) {
            //         $tenantFilenameWithExt = $file->getClientOriginalName();
            //         $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
            //         $tenantExtension = $file->getClientOriginalExtension();
            //         $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
            //         // $dir = storage_path('upload/tenant');
            //         $dir = storage_path('app/public/upload/tenant'); // Corrected path for storage

            //         if (!file_exists($dir)) {
            //             mkdir($dir, 0777, true);
            //         }
            //         // $file->storeAs('upload/tenant/', $tenantFileName);
            //         $file->storeAs('public/upload/tenant/', $tenantFileName); // Corrected storage path

            //         $tenantImage->contract = $tenantFileName;
            //         // $tenantImage->save();
            //     }
            // // }

            // // if (!empty($request->memorandum)) {
            //     foreach ($request->memorandum as $file) {
            //         $tenantFilenameWithExt = $file->getClientOriginalName();
            //         $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
            //         $tenantExtension = $file->getClientOriginalExtension();
            //         $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
            //         // $dir = storage_path('upload/tenant');
            //         $dir = storage_path('app/public/upload/tenant'); // Corrected path for storage

            //         if (!file_exists($dir)) {
            //             mkdir($dir, 0777, true);
            //         }
            //         // $file->storeAs('upload/tenant/', $tenantFileName);
            //         $file->storeAs('public/upload/tenant/', $tenantFileName); // Corrected storage path

            //         $tenantImage->memorandum = $tenantFileName;
            //         // $tenantImage->save();
            //     }
            // // }

            // // if (!empty($request->trading_licence)) {
            //     foreach ($request->trading_licence as $file) {
            //         $tenantFilenameWithExt = $file->getClientOriginalName();
            //         $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
            //         $tenantExtension = $file->getClientOriginalExtension();
            //         $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
            //         // $dir = storage_path('upload/tenant');
            //         $dir = storage_path('app/public/upload/tenant'); // Corrected path for storage

            //         if (!file_exists($dir)) {
            //             mkdir($dir, 0777, true);
            //         }
            //         // $file->storeAs('upload/tenant/', $tenantFileName);
            //         $file->storeAs('public/upload/tenant/', $tenantFileName);
            //         $tenantImage->trading_licence = $tenantFileName;
            //         // $tenantImage->save();
            //     }
            // // }

            // // if (!empty($request->application_flow)) {
            //     foreach ($request->application_flow as $file) {
            //         $tenantFilenameWithExt = $file->getClientOriginalName();
            //         $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
            //         $tenantExtension = $file->getClientOriginalExtension();
            //         $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
            //         // $dir = storage_path('upload/tenant');
            //         $dir = storage_path('app/public/upload/tenant');
            //         if (!file_exists($dir)) {
            //             mkdir($dir, 0777, true);
            //         }
            //         // $file->storeAs('upload/tenant/', $tenantFileName);
            //         $file->storeAs('public/upload/tenant/', $tenantFileName);
            //         $tenantImage->application_flow = $tenantFileName;
            //         // $tenantImage->save();
            //     }
            // // }


            $tenantImage->parent_id =parentId();
            $tenantImage->save();

            // $date1 = Carbon::parse($request->lease_start_date);
            // $date2 = Carbon::parse($request->lease_end_date);

            // $monthsDifference = $date1->diffInMonths($date2);

            // $contract = new Contract();
            // $contract->user_id = $user->id;
            // $contract->tenant_id = $tenant->id;
            // $contract->lease_tenure =$monthsDifference;
            // $contract->save();

            return response()->json([
                'status' => 'success',
                'msg' => __('Tenant successfully created.'),

            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function show(Tenant $tenant)
    {
        if (\Auth::user()->can('show tenant')) {
            return view('tenant.show', compact('tenant'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function edit(Tenant $tenant)
    {
        if (\Auth::user()->can('edit tenant')) {
            $property = Property::where('parent_id',parentId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);

            $user=User::find($tenant->user_id);
            return view('tenant.edit', compact('property', 'tenant','user'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function update(Request $request, Tenant $tenant)
    {
        if (\Auth::user()->can('edit tenant')) {
            $validator = \Validator::make(
                $request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone_number' => 'required',
                'title' => 'required',
                'business_name' => 'required',
                'business_number' => 'required',
                'tax_payer_identification' => 'required',
                'contact_information' => 'required',
                'address' => 'required',
                'property' => 'required',
                'unit' => 'required',
                'lease_start_date' => 'required',
                'lease_end_date' => 'required',
            ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),

                ]);

            }

            $user=User::find($tenant->user_id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->save();

            if ($request->profile!='') {
                $tenantFilenameWithExt = $request->file('profile')->getClientOriginalName();
                $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                $tenantExtension = $request->file('profile')->getClientOriginalExtension();
                $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
                $dir = storage_path('upload/profile');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('profile')->storeAs('upload/profile/', $tenantFileName);
                $user->profile = $tenantFileName;
                $user->save();
            }

            $tenant->title = $request->title;
            $tenant->business_name = $request->business_name;
            $tenant->business_number = $request->business_number;
            $tenant->tax_payer_identification = $request->tax_payer_identification;
            $tenant->contact_information = $request->contact_information;
            $tenant->address = $request->address;
            $tenant->property = $request->property;
            $tenant->unit = $request->unit;
            $tenant->lease_start_date = $request->lease_start_date;
            $tenant->lease_end_date = $request->lease_end_date;
            $tenant->save();



            if (!empty($request->tenant_images)) {
                foreach ($request->tenant_images as $file) {
                    $tenantFilenameWithExt = $file->getClientOriginalName();
                    $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                    $tenantExtension = $file->getClientOriginalExtension();
                    $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
                    $dir = storage_path('upload/tenant');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $file->storeAs('upload/tenant/', $tenantFileName);

                    $tenantImage = new TenantDocument();
                    $tenantImage->property_id = $request->property;
                    $tenantImage->tenant_id = $tenant->id;
                    $tenantImage->document = $tenantFileName;
                    $tenantImage->parent_id =parentId();
                    $tenantImage->save();
                }
            }

            return response()->json([
                'status' => 'success',
                'msg' => __('Tenant successfully updated.'),
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function destroy(Tenant $tenant)
    {
        if (\Auth::user()->can('delete tenant')) {
            $tenant->delete();
            return redirect()->back()->with('success', 'Tenant successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

}
