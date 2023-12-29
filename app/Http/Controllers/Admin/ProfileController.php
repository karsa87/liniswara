<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LogHistoryEnum;
use App\Enums\Preorder\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdateEmailRequest;
use App\Http\Requests\Admin\Profile\UpdateInfoRequest;
use App\Http\Requests\Admin\Profile\UpdatePasswordRequest;
use App\Models\LogHistory;
use App\Models\Order;
use App\Models\Preorder;
use App\Models\User;
use App\Utils\Phone;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->loadMissing('role', 'roles', 'profile_photo');

        $total = [
            'preorder' => Preorder::where('created_by', $user->id)->sum('total_amount'),
            'order' => Order::where('created_by', $user->id)->where('status', [
                StatusEnum::VALIDATION_ADMIN,
                StatusEnum::PROCESS,
            ])->sum('total_amount'),
            'order_sent' => Order::where('created_by', $user->id)->where('status', [
                StatusEnum::SENT,
            ])->sum('total_amount'),
            'order_arsip' => Order::where('created_by', $user->id)->where('status', [
                StatusEnum::DONE,
            ])->sum('total_amount'),
        ];

        return view('admin.profile.index', [
            'user' => $user,
            'total' => $total,
        ]);
    }

    public function update(UpdateInfoRequest $request)
    {
        $user = auth()->user();
        $user->fill([
            'name' => $request->input('name'),
            'company' => $request->input('company') ?? '',
            'phone_number' => Phone::normalize($request->input('phone')),
            'profile_photo_id' => $request->input('profile_photo_id'),
        ]);

        $user->save();

        return response()->json();
    }

    public function update_email(UpdateEmailRequest $request)
    {
        $user = auth()->user();
        $oldEmail = $user->email;
        $user->email = $request->input('emailaddress');
        $user->save();

        LogHistory::create([
            'log_datetime' => now(),
            'user_id' => $user->id,
            'record_id' => $user->id,
            'data_after' => [
                'email' => $user->email,
            ],
            'data_before' => [
                'email' => $oldEmail,
            ],
            'transaction_type' => LogHistoryEnum::TRANSACTION_UPDATE,
            'information' => sprintf('Update email %s', $user->email),
            'record_type' => User::class,
            'table' => (new User())->getTable(),
        ]);

        return response()->json();
    }

    public function update_password(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        $oldEmail = $user->email;
        $user->password = $request->input('password');
        $user->save();

        LogHistory::create([
            'log_datetime' => now(),
            'user_id' => $user->id,
            'record_id' => $user->id,
            'data_after' => [
                'email' => $user->email,
            ],
            'data_before' => [
                'email' => $oldEmail,
            ],
            'transaction_type' => LogHistoryEnum::TRANSACTION_UPDATE,
            'information' => sprintf('Update password %s', $user->name),
            'record_type' => User::class,
            'table' => (new User())->getTable(),
        ]);

        return response()->json();
    }
}
