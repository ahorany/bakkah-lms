<div class="{{$cls}}">
    {{-- card p-30 learning-file activity --}}
    <h3>{{__('education.Activity Completed')}}</h3>
    <ul style="list-style: none; padding: 0;">
        <?php $lang = app()->getLocale(); ?>
        @foreach($activities as $activity)
            <li>
                <img class="activity_icon" width="28.126" height="28.127" style="vertical-align: middle;" src="{{CustomAsset('icons/activity.svg')}}" alt="activity_icon">
                @if($activity->type == 'exam')
                    <a style="color: #6a6a6a !important;" href="{{ CustomRoute('user.exam',$activity->content_id)}}">{{$activity->content_title}} - ({{ json_decode($activity->course_title)->$lang }})</a>
                @else
                    <a style="color: #6a6a6a !important;" href="{{ CustomRoute('user.course_preview',$activity->content_id)}}">{{$activity->content_title}} - ({{ json_decode($activity->course_title)->$lang }})</a>
                @endif
            </li>
        @endforeach
    </ul>
</div>
{{--
    <div class="card h-100 calendar">
        <div class="sideb">
            <div class="header">
                <i class="fa fa-angle-left" aria-hidden="true">
                    <svg id="Group_103" data-name="Group 103" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                        <path id="Path_99" data-name="Path 99" d="M161.171,218.961a1.511,1.511,0,0,1-1.02-.4l-11.823-10.909a1.508,1.508,0,0,1,0-2.215l11.823-10.912a1.508,1.508,0,0,1,2.045,2.215l-10.625,9.8,10.625,9.8a1.508,1.508,0,0,1-1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#333"/>
                    </svg>
                </i>
                <h3>
                    <span class="month"></span>
                    <span class="year"></span>
                </h3>
                <i class="fa fa-angle-right" aria-hidden="true"><svg id="Group_104" data-name="Group 104" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                    <path id="Path_99" data-name="Path 99" d="M149.351,218.961a1.511,1.511,0,0,0,1.02-.4l11.823-10.909a1.508,1.508,0,0,0,0-2.215l-11.823-10.912a1.508,1.508,0,0,0-2.045,2.215l10.625,9.8-10.625,9.8a1.508,1.508,0,0,0,1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#333"/>
                </svg>
                </i>
            </div>
            <div class="calender">
                <table>
                    <thead>
                        <tr class="weedays">
                            <th data-weekday="sun" data-column="0">Sun</th>
                            <th data-weekday="mon" data-column="1">Mon</th>
                            <th data-weekday="tue" data-column="2">Tue</th>
                            <th data-weekday="wed" data-column="3">Wed</th>
                            <th data-weekday="thu" data-column="4">Thu</th>
                            <th data-weekday="fri" data-column="5">Fri</th>
                            <th data-weekday="sat" data-column="6">Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="days" data-row="0">
                            <td data-column="0"></td>
                            <td data-column="1"></td>
                            <td data-column="2"></td>
                            <td data-column="3"></td>
                            <td data-column="4"></td>
                            <td data-column="5"></td>
                            <td data-column="6"></td>
                        </tr>
                        <tr class="days" data-row="1">
                            <td data-column="0"></td>
                            <td data-column="1"></td>
                            <td data-column="2"></td>
                            <td data-column="3"></td>
                            <td data-column="4"></td>
                            <td data-column="5"></td>
                            <td data-column="6"></td>
                        </tr>
                        <tr class="days" data-row="2">
                            <td data-column="0"></td>
                            <td data-column="1"></td>
                            <td data-column="2"></td>
                            <td data-column="3"></td>
                            <td data-column="4"></td>
                            <td data-column="5"></td>
                            <td data-column="6"></td>
                        </tr>
                        <tr class="days" data-row="3">
                            <td data-column="0"></td>
                            <td data-column="1"></td>
                            <td data-column="2"></td>
                            <td data-column="3"></td>
                            <td data-column="4"></td>
                            <td data-column="5"></td>
                            <td data-column="6"></td>
                        </tr>
                        <tr class="days" data-row="4">
                            <td data-column="0"></td>
                            <td data-column="1"></td>
                            <td data-column="2"></td>
                            <td data-column="3"></td>
                            <td data-column="4"></td>
                            <td data-column="5"></td>
                            <td data-column="6"></td>
                        </tr>
                        <tr class="days" data-row="5">
                            <td data-column="0"></td>
                            <td data-column="1"></td>
                            <td data-column="2"></td>
                            <td data-column="3"></td>
                            <td data-column="4"></td>
                            <td data-column="5"></td>
                            <td data-column="6"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
 --}}
