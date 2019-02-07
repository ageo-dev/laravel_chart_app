@extends('layouts.admin') 
@section('content')

<div class="row">
    <div class="col-xs-12">
        <h2>Tutorial</h2>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#sampleContentA" data-toggle="tab">Introduction</a></li>
            <li><a href="#sampleContentB" data-toggle="tab">First of all</a></li>
            <li><a href="#sampleContentC" data-toggle="tab">About adding users</a></li>
            <li><a href="#sampleContentD" data-toggle="tab">About schedule</a></li>
            <li><a href="#sampleContentE" data-toggle="tab">About bulk editing</a></li>
            <li><a href="#sampleContentF" data-toggle="tab">About the chart(*important)</a></li>
            <li><a href="#sampleContentG" data-toggle="tab">If you want to change the capital to 0</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="sampleContentA">
                <h3>What can be done with this system</h3>
                <hr>
                <p>You can create a virtual asset management chart.</p>
                <p>Since we can create charts of both the past and the future, it is possible to simulate when we manage assets in some way.</p>
                <p>You can also increase or decrease assets by adding assets or by schedule.</p>
                <p>The terminology of the function in using this system is summarized in a separate tab on this page, so please read this entirely before actually simulating.</p>
            </div>
            <div class="tab-pane" id="sampleContentB">
                <h3>Please set up first</h3>
                <hr>
                <p>First of all, please set basic setting and category name setting by menu "Setting".</p>
                <ul>
                    <li>
                        Chart update time
                        <ul>
                            <li>You can change the chart update time of the user by updating here.</li>
                        </ul>
                    </li>
                    <li>
                        Chart update minute
                        <ul>
                            <li>By updating this you can change the fraction of the user's chart update time.</li>
                        </ul>
                    </li>
                    <li>
                        Automatic increase rate
                        <ul>
                            <li>Automatically increase / decrease the chart of users who have not registered schedule.</li>
                            <li>This incremental rate of total assets is added (or subtracted).</li>
                        </ul>
                    </li>
                    <li>
                        Number of data reads
                        <ul>
                            <li>You can set the maximum number of data reads on the management screen page.</li>
                            <li>If you want to acquire a large amount of data at once, please change it to 100, 1000, etc.</li>
                        </ul>
                    </li>
                    <li>
                        Category name setting
                        <ul>
                            <li>You can set the names of various categories (groups).</li>
                            <li>Dividing categories makes schedule management and bulk editing easier.</li>
                            <li>When registering a user, it will be allocated to the initial category.</li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" id="sampleContentC">
                <h3>How to add users</h3>
                <hr>
                <p>The user addition method can be performed only by the administrator with the administrator authority being the highest authority ~ the registrable only authority.</p>
                <p>You can add from the menu "Add user".</p>
                <p>Please input by entering mail address, password, capital, and operation start date.</p>
            </div>
            <div class="tab-pane" id="sampleContentD">
                <h3>About schedule</h3>
                <hr>
                <p>In this system, we can perform user's investment simulation by "basic schedule" and "custom schedule".</p>
                <p>In the system, "basic schedule" is abbreviated as "BS (abbreviation for base schedule)", custom schedule is abbreviated as "CS".</p>
                <ul>
                    <li>
                        About BS
                        <ul>
                            <li>BS is a schedule that can be managed by category.</li>
                            <li>Users who are not set up will automatically increase or decrease the asset depending on the automatic increase rate or custom automatic increase rate (increase rate that can be set for each user).</li>
                            <li><span class="red">CS takes precedence over this schedule.</span></li>
                        </ul>
                    </li>
                    <li>
                        About CS
                        <ul>
                            <li>CS is a schedule that can be managed separately for each user.</li>
                        </ul>
                    </li>

                </ul>
            </div>
            <div class="tab-pane" id="sampleContentE">
                <h3>About bulk editing</h3>
                <hr>
                <p>When editing user information or bulk editing of CS, you can do a user who wants to perform bulk editing first after searching from the menu "User search".</p>
                <p>In case of batch editing to rewrite the past chart, it may take time depending on the number of users.</p>
            </div>
            <div class="tab-pane" id="sampleContentF">
                <h3>About the chart</h3>
                <hr>
                <p>Only the "past chart" of the user's chart is logged (stored in the database).</p>
                <p>The past chart is logged as "When the user or administrator opened the site after the date changed".</p>
                <p>Therefore, the administrator recommends opening the site once a day when using this system.</p>
                <p><small class="red">In order to be updated automatically every day, we need not perform server maintenance, so we did not implement this time.</small></p>
            </div>
            <div class="tab-pane" id="sampleContentG">
                <h3>If you want to change the capital to 0</h3>
                <hr>
                <p>Please enter a minus value which is obviously higher than the capital stock possessed by the user, such as "-1000000000" by adding additional capital</p>
                <p><small class="red">The capital is set to 0, that is, the capital is returned to the user, so the asset also becomes 0, and the number is automatically changed as if the capital addition log was also fully negated.</small></p>
            </div>
        </div>

    </div>
</div>
@endsection