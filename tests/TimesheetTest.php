<?php

use App\Models\Timesheet;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class TimesheetTest extends TestCase
{
    public function testIndex()
    {
        $this->get('/api/timesheet')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                "timesheets" => ['*' => [
                    "id",
                    "is_approved",
                    "user_id",
                    "created_at",
                    "user" => [
                        "id",
                        "email",
                        "is_admin",
                        "is_login",
                        "employee_id",
                        "created_at",
                        "updated_at",
                        "employee" => [
                            "id",
                            "first_name",
                            "mid_name",
                            "last_name",
                            "phone",
                            "gender",
                            "birthday",
                            "salary",
                            "job_position",
                            "rating",
                            "user_id",
                            "image_id",
                            "address_id",
                            "supervisor_id",
                            "department_id",
                            "created_at",
                            "updated_at",
                            "full_name"
                        ]
                    ],
                ]],
                "today_timesheets" => [
                    '*' => [
                        'id',
                        'is_approved',
                        'user_id',
                        'created_at',
                        'user' => [
                            "id",
                            "email",
                            "is_admin",
                            "is_login",
                            "employee_id",
                            "created_at",
                            "updated_at",
                            "employee" => [
                                "id",
                                "first_name",
                                "mid_name",
                                "last_name",
                                "phone",
                                "gender",
                                "birthday",
                                "salary",
                                "job_position",
                                "rating",
                                "user_id",
                                "image_id",
                                "address_id",
                                "supervisor_id",
                                "department_id",
                                "created_at",
                                "updated_at",
                                "full_name"
                            ]
                        ],
                        'activities' => [
                            '*' => [
                                'id',
                                'desc',
                                'start_time',
                                'stop_time',
                                'timesheet_id'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldCreateTimesheetSuccess() {
        $data = [
            'activities' => [
                [
                    'desc' => 'Tes1',
                    'start_time' => "07:48",
                    'stop_time' => "10:48"
                ],
                [
                    'desc' => 'Tes2',
                    'start_time' => "11:48",
                    'stop_time' => "16:48"
                ]
            ]
        ];
        $user = User::latest()->first();
        $token = JWTAuth::fromUser($user);
        $this->post('/api/timesheet?token='.$token, $data)
            ->seeStatusCode(201)
            ->seeJsonEquals([
                'message' => 'Successfully create timesheet'
            ]);
    }

    public function testShouldCreateTimesheetFailed() {
        $data = [
            'activities' => [
                [
                    'desc' => 'Tes1',
                    'start_time' => "07:48",
                    'stop_time' => "10:48"
                ],
                [
                    'desc' => 'Tes2',
                    'start_time' => "11:48",
                    'stop_time' => "16:48"
                ]
            ]
        ];
        $this->post('/api/timesheet', $data)
            ->seeStatusCode(401);
    }

    public function testShouldApproveSuccess() {
        $user = User::latest()->first();
        $timesheet = Timesheet::latest()->first();
        $user->is_admin = true;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->put('/api/timesheet/approve/'. $timesheet->id . '?token='.$token)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'message' => 'Timesheet approved successfully'
            ]);
    }

    public function testShouldApproveFailed() {
        $user = User::latest()->first();
        $timesheet = Timesheet::latest()->first();
        $user->is_admin = false;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->put('/api/timesheet/approve/'. $timesheet->id .'?token='.$token)
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'message' => 'You are not admin'
            ]);
    }

    public function testShouldDeleteFailed() {
        $user = User::latest()->first();
        $timesheet = Timesheet::latest()->first();
        $user->is_admin = false;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/timesheet/'. $timesheet->id .'?token='.$token)
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'message' => 'You are not admin'
            ]);
    }

    public function testShouldDeleteSuccess() {
        $user = User::latest()->first();
        $timesheet = Timesheet::latest()->first();
        $user->is_admin = true;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/timesheet/'. $timesheet->id .'?token='.$token)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'message' => 'Timesheet deleted successfully'
            ]);
    }

    public function testShouldClearSuccess() {
        $user = User::latest()->first();
        $user->is_admin = true;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/timesheet/clear?token='.$token)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'message' => 'Timesheet cleared'
            ]);
    }

    public function testShouldClearFailed() {
        $user = User::latest()->first();
        $user->is_admin = false;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/timesheet/clear?token='.$token)
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'message' => 'You are not admin'
            ]);
    }
}
