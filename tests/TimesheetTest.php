<?php

use App\Models\Activity;
use App\Models\Timesheet;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\WithoutMiddleware;

class TimesheetTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/timesheet', []);
        $response->seeStatusCode(200)
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
        $params = [
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
        $user = User::find(1);
        $user->is_login = true;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->post('/api/timesheet?token='.$token, $params, [])
            ->seeStatusCode(201);
        $user->is_login = false;
        $user->save();
    }

    public function testShouldCreateTimesheetFailed() {
        $params = [
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
        $user = User::find(1);
        $user->is_login = false;
        $user->save();
        $this->post('/api/timesheet', $params, [])
            ->seeStatusCode(401);
    }

    // public function testShouldApproveSuccess() {
    //     $user = User::find(1);
    //     $user->is_login = true;
    //     $user->is_admin = true;
    //     $user->save();
    //     $token = JWTAuth::fromUser($user);
    //     $this->put('/api/timesheet/approve/6?token='.$token, [])
    //         ->seeStatusCode(200);
    //     $user->is_login = false;
    //     $user->save();
    // }

    public function testShouldApproveFailed() {
        $user = User::find(2);
        $user->is_login = true;
        $user->is_admin = false;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->put('/api/timesheet/approve/6?token='.$token, [])
            ->seeStatusCode(400);
        $user->is_login = false;
        $user->save();
    }
    
    public function testShouldDeleteSuccess() {
        $user = User::find(1);
        $user->is_login = true;
        $user->is_admin = true;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $timesheetId = Timesheet::all()->pluck("id")->random();
        $this->delete('/api/timesheet/'.strval($timesheetId).'?token='.$token, [])
            ->seeStatusCode(200);
        $user->is_login = false;
        $user->save();
    }

    public function testShouldDeleteFailed() {
        $user = User::find(2);
        $user->is_login = true;
        $user->is_admin = false;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/timesheet/6?token='.$token, [])
            ->seeStatusCode(400);
        $user->is_login = false;
        $user->save();
    }

    public function testShouldClearSuccess() {
        $user = User::find(1);
        $user->is_login = true;
        $user->is_admin = true;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/timesheet/clear?token='.$token, [])
            ->seeStatusCode(200);
        $user->is_login = false;
        $user->save();
    }

    public function testShouldClearFailed() {
        $user = User::find(2);
        $user->is_login = true;
        $user->is_admin = false;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/timesheet/clear?token='.$token, [])
            ->seeStatusCode(400);
        $user->is_login = false;
        $user->save();
    }
}
