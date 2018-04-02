@extends('layouts.main')

@section('content')
    <!--Basic Tabs   -->
    <div class="panel panel-default mu_tab_wr">
        <div class="panel-heading">
            Basic Tabs
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Customer API</a>
                </li>
                <li><a href="#profile" data-toggle="tab">Salon API</a>
                </li>

            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <h4>Customer API</h4>
                    <div class="mu_accord_wr">
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Check</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/</span>
                                        <span>Check connection</span>
                                    </div>
                                    <div class="accord_body_body container-fluid">
                                        <span>Parameters</span>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <h5>No parameters</h5>

                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9"></div>
                                        </div>
                                        <span>Responce</span>
                                        <div class="row" style="background-color:lightcyan;">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <h5>Code</h5>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <h5>Description</h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <h5 style="margin-left:10px">200</h5>
                                                <h5 style="margin-left:10px">400</h5>
                                                <h5 style="margin-left:10px">404</h5>
                                                <h5 style="margin-left:10px">405</h5>
                                                <h5 style="margin-left:10px">500</h5>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <h5 style="margin-left:10px">Connection success</h5>
                                                <h5 style="margin-left:10px">Bad request</h5>
                                                <h5 style="margin-left:10px">Not found</h5>
                                                <h5 style="margin-left:10px">Method Not Allowed</h5>
                                                <h5 style="margin-left:10px">Server error</h5>
                                            </div>
                                        </div>
                                        <div><button class="btn btn-primary" id="sendCheckConnectionBtn" style="margin-left:10px">Send request</button></div>
                                        <div class="container-fluid" id="checkConnectionResult"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Admin</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/admin/message</span>
                                        <span>Message to admin</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Authorization</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/singin</span>
                                        <span>User authorisation</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/singout</span>
                                        <span>Stop using program.</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/singup/customer</span>
                                        <span>New user Profile</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/edit/customer</span>
                                        <span>Edit customer Profile</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/newPassword</span>
                                        <span>Change Urer's password.</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/forgot_password/{email}</span>
                                        <span>New temp password</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Confirm e-mail</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/auth/confirm_email/{user_id}</span>
                                        <span>Confirm e-mail</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Delete user</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/del</span>
                                        <span>Delete user</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Upload file</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/upload</span>
                                        <span>New user Profile</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Search Salon</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/salon/search/{lat}/{lng}/{radius}</span>
                                        <span>Search salon around customer</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/salon/search/{city}</span>
                                        <span>Search salon around customer</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Comments</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/salon/comments/{salon_id}</span>
                                        <span>Get Salon comments</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/salon/comments/{salon_id}</span>
                                        <span>Post new Salon's comments</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_put">
                                        <span class="mu_button mu_button_or">PUT</span>
                                        <span>/salon/comments/{salon_id}/{comment_id}</span>
                                        <span>Change Salon comments</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_del">
                                        <span class="mu_button mu_button_red">DELETE</span>
                                        <span>/salon/comments/{salon_id}/{comment_id}</span>
                                        <span>Delete Salon's comments</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Ratings</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/salon/rating/{salon_id}</span>
                                        <span>Get Salon comments</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/salon/rating/{salon_id}</span>
                                        <span>Post new Salon's comments</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr accord_wr_model">
                            <div class="accord_head">
                                <span>Models</span>
                            </div>
                            <div class="accord_body">
                                <span>Some text</span>
                            </div>
                        </div>
                    </div>

                    <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> -->


                </div>
                <div class="tab-pane fade" id="profile">
                    <h4>Salon API</h4>
                    <div class="mu_accord_wr">
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Check</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/</span>
                                        <span>Check connection</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Authorization</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/singin</span>
                                        <span>User authorisation</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/singout</span>
                                        <span>Stop using program.</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/singup/salon</span>
                                        <span>New salon Profile</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/singup/worker/start</span>
                                        <span>New worker's Profile</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/singup/worker/complete</span>
                                        <span>Worker's profile, add worker data</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Edit</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/edit/salon</span>
                                        <span>Edit salon Profile</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/auth/edit/worker</span>
                                        <span>Worker's profile, edit worker data</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Salon's Services</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/service/salon/{salon_id}</span>
                                        <span>Get services for this Salon</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/service/salon/{salon_id}</span>
                                        <span>Make new service for this Salon</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_put">
                                        <span class="mu_button mu_button_or">PUT</span>
                                        <span>/service/salon/{salon_id}/{service_id}</span>
                                        <span>Edit Salon's service</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_del">
                                        <span class="mu_button mu_button_red">DELETE</span>
                                        <span>/service/salon/{salon_id}/{service_id}</span>
                                        <span>Delete Salon's service</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/service/salon/{salon_id}/upload/{service_id}</span>
                                        <span>Load Logo for Service</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Services</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/service/worker/{worker_id}</span>
                                        <span>Get Worker's services list</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head  accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/service/worker/{worker_id}/{service_id}</span>
                                        <span>Add service for Worker services list</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_put">
                                        <span class="mu_button mu_button_or">PUT</span>
                                        <span>/service/worker/{worker_id}/{service_id}</span>
                                        <span>Edit Worker's services list</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_del">
                                        <span class="mu_button mu_button_red">DELETE</span>
                                        <span>/service/worker/{worker_id}/{service_id}</span>
                                        <span>Delete service from Worker's Service list</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Salon's Workers</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/salon/workers/{salon_id}</span>
                                        <span>Get Workers for this Salon</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/salon/workers/service/{worker_id}</span>
                                        <span>Get Workers services</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Schedules</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/worker/schedule/{worker_id}</span>
                                        <span>Get schedule for Worker</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/worker/schedule/{worker_id}</span>
                                        <span>Add schedule for Worker</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_del">
                                        <span class="mu_button mu_button_red">DELETE</span>
                                        <span>/worker/schedule/{worker_id}/{schedule_id}</span>
                                        <span>Delete schedule for Worker</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/worker/schedules/{worker_id}</span>
                                        <span>IT DOESN'T WORK RIGHT NOW, MAY BE IN FUTURE RELEASES</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Queue</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/queue/worker/{worker_id}</span>
                                        <span>Get Queue</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/queue/salon/{salon_id}</span>
                                        <span>Get Queue for Salon</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/queue/customer/{customer_id}</span>
                                        <span>Get Queue for Customer</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_get">
                                        <span class="mu_button mu_button_bl">GET</span>
                                        <span>/queue/confirm/{queue_id}</span>
                                        <span>Confirm Queue</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/queue/{worker_id}/{service_id}</span>
                                        <span>Set Queue</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_del">
                                        <span class="mu_button mu_button_red">DELETE</span>
                                        <span>/queue/{queue_id}</span>
                                        <span>Delete Queue</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr">
                            <div class="accord_head">
                                <span>Schedules</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </div>
                            <div class="accord_body">
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/notification</span>
                                        <span>Test notification</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="accord_body_head accord_body_head_post">
                                        <span class="mu_button">POST</span>
                                        <span>/notification/set_token</span>
                                        <span>Set notification token</span>
                                    </div>
                                    <div class="accord_body_body">
                                        <span>Some text</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accord_wr accord_wr_model">
                            <div class="accord_head">
                                <span>Models</span>
                            </div>
                            <div class="accord_body">
                                <span>Some text</span>
                            </div>
                        </div>
                        <!--  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script>
            $('.accord_head').on('click', function () {
                $(this).toggleClass('active');
                $(this).siblings('.accord_body').toggleClass('active');
            })
            $('.accord_body_head').on('click', function () {
                $(this).toggleClass('active');
                $(this).siblings('.accord_body_body').toggleClass('active');
            })
        </script>
        <!--End Basic Tabs   -->
        <script type="text/javascript" src="/public/js/api.js"></script>

@stop