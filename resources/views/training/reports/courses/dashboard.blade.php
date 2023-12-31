

@if(isset($course[0]->id))
    <a href="{{route('training.coursesReportOverview',['id'=>$course[0]->id??null,'export'=>1,'show_all'=>0])}}" class="export btn-sm">
        <span class="icon">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                <path d="M50.07,82.5c-9.62,0-19.24,0-28.86,0c-6.13,0-10.74-4.59-10.74-10.71c0-8.3,0-16.6,0-24.9c0-2.13,1.11-3.59,2.95-3.94
                    c2.15-0.4,4.16,1.16,4.25,3.36c0.04,1.01,0.01,2.03,0.01,3.04c0,7.45,0,14.91,0,22.36c0,2.23,1.34,3.56,3.57,3.56
                    c19.21,0,38.43,0,57.64,0c2.29,0,3.61-1.31,3.61-3.6c0-8.33,0-16.65,0-24.98c0-2.56,2.07-4.27,4.46-3.71
                    c1.64,0.38,2.73,1.82,2.74,3.69c0.02,2.14,0.01,4.28,0.01,6.41c0,6.22,0,12.43,0,18.65c0,5.52-3.66,9.8-9.11,10.65
                    c-0.55,0.09-1.12,0.1-1.68,0.1C69.31,82.5,59.69,82.5,50.07,82.5z"/>
                <path d="M46.5,30.02c-1.79,1.77-3.41,3.41-5.06,5.01c-1.69,1.64-4.29,1.4-5.58-0.48c-1.01-1.48-0.82-3.29,0.54-4.69
                    c3.44-3.52,6.9-7.03,10.36-10.52c0.41-0.41,0.88-0.78,1.37-1.09c1.41-0.9,3.17-0.8,4.36,0.37c3.85,3.79,7.67,7.6,11.46,11.46
                    c1.42,1.45,1.28,3.71-0.17,5.08c-1.41,1.34-3.58,1.31-5.02-0.09c-1.47-1.44-2.9-2.91-4.35-4.36c-0.19-0.19-0.4-0.37-0.74-0.69
                    c0,0.48,0,0.77,0,1.07c0,9.73,0,19.47-0.01,29.2c0,0.58-0.02,1.2-0.19,1.75c-0.52,1.7-2.15,2.66-3.95,2.42
                    c-1.64-0.22-2.92-1.65-3.01-3.36c-0.02-0.45-0.01-0.9-0.01-1.35c0-9.54,0-19.07,0-28.61C46.5,30.82,46.5,30.52,46.5,30.02z"/>
            </svg>
        </span>
        <span>{{__('admin.export')}}</span>
    </a>
@endif


<div class="row mb-4 dashboard">

    <div class="col-lg-12 p-0">
        <div class="card h-100 p-30">

            <div class="d-flex flex-column flex-sm-row flex-wrap justify-content-center">
            @if(isset($count) && !isset($show_all))
                <div class="course-cards" style="border-color: rgb(48 61 71 / 50%)">
                    <svg version="1.1" class="icon-report bg-four" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:none;stroke:#fff;stroke-width:3.0215;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22.9256;}
                            .st1{fill:none;stroke:#fff;stroke-width:3.0221;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22.9256;}
                        </style>
                        <g>
                            <polyline class="st0" points="81.49,25.18 86.65,25.18 86.65,70.19 12.29,70.19 12.29,25.18 17.45,25.18 	"/>
                            <path class="st1" d="M31.38,83.94h36.19 M43.28,82.59V72.01 M55.66,82.59V72.01 M26.35,24.55c9.22,0,10.82,0,15.53,0 M26.35,32.64
                                c9.22,0,10.82,0,15.53,0 M26.35,40.74c9.22,0,10.82,0,15.53,0 M26.35,48.84c9.22,0,10.82,0,15.53,0 M18.96,16.33
                                c6.84,0,19.8,0,26.63,0l3.69,4.02c0,13.87,0,27.04,0,40.91l-3.69-4.02c-6.84,0-19.8,0-26.63,0C18.96,43.37,18.96,30.2,18.96,16.33
                                L18.96,16.33z M53.35,16.33l-3.69,4.02 M49.66,61.27l3.69-4.02 M53.35,57.25c6.84,0,19.79,0,26.63,0c0-13.87,0-27.04,0-40.91
                                c-6.84,0-19.79,0-26.63,0"/>
                            <polyline class="st0" points="79.99,57.77 79.99,63.65 18.95,63.65 18.95,57.64 	"/>
                            <path class="st1" d="M57.05,24.55c9.22,0,10.82,0,15.53,0 M57.05,32.64c9.22,0,10.82,0,15.53,0 M57.05,40.74
                                c9.22,0,10.82,0,15.53,0"/>
                        </g>
                    </svg>
                    <div>
                        <span>{{__('admin.courses')}}</span>
                        <b>{{$count}}</b>
                    </div>
                </div>
            @endif

            @if(isset($assigned_learners))
                <div class="course-cards" style="border-color: rgb(251 68 0 / 50%);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="85.644" height="75.049" viewBox="0 0 85.644 75.049" class="icon-report bg-main">
                        <g id="Group_178" data-name="Group 178" transform="translate(-448.032 56.166)">
                          <g id="Group_319" data-name="Group 319" transform="translate(448.818 -55.416)">
                            <g id="Group_176" data-name="Group 176" transform="translate(0 0)">
                              <path id="Path_166" data-name="Path 166" d="M512.178,34.445H472.911a2.768,2.768,0,0,1-2.4-1.384L450.88-.946a2.77,2.77,0,0,1,0-2.768L470.514-37.72a2.768,2.768,0,0,1,2.4-1.384h39.267a2.766,2.766,0,0,1,2.4,1.384L534.21-3.714a2.77,2.77,0,0,1,0,2.768L514.576,33.061A2.766,2.766,0,0,1,512.178,34.445ZM474.51,28.909h36.071L528.615-2.33,510.581-33.568H474.51L456.474-2.33Z" transform="translate(-450.509 39.104)" fill="#fff" stroke="#fff" stroke-width="1.5"/>
                            </g>
                            <g id="Group_177" data-name="Group 177" transform="translate(17.687 12.156)">
                              <path id="Path_167" data-name="Path 167" d="M500.974,16.264a2.769,2.769,0,0,1-1.289-.318L487.638,9.61l-12.05,6.335a2.767,2.767,0,0,1-4.016-2.918l2.3-13.418-9.748-9.5a2.766,2.766,0,0,1-.7-2.837,2.77,2.77,0,0,1,2.235-1.884l13.471-1.958,6.025-12.206a2.769,2.769,0,0,1,2.483-1.543h0a2.767,2.767,0,0,1,2.482,1.543l6.025,12.206,13.471,1.958a2.766,2.766,0,0,1,2.235,1.884,2.768,2.768,0,0,1-.7,2.837L501.4-.391l2.235,13.035A2.771,2.771,0,0,1,501,16.264.182.182,0,0,0,500.974,16.264ZM472-9.942l6.774,6.6a2.767,2.767,0,0,1,.8,2.45l-1.6,9.324,8.374-4.4a2.778,2.778,0,0,1,2.576,0l8.374,4.4L495.7-.889a2.766,2.766,0,0,1,.8-2.45l6.774-6.6L493.908-11.3a2.773,2.773,0,0,1-2.084-1.514L487.638-21.3l-4.187,8.483a2.773,2.773,0,0,1-2.084,1.514Z" transform="translate(-463.288 30.321)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                            </g>
                          </g>
                        </g>
                      </svg>
                    <div>
                        <span>{{__('admin.assigned_learners')}}</span>
                        <b>{{$assigned_learners}}</b>
                    </div>
                </div>
            @endif

            @if(isset($assigned_instructors))
                <div class="course-cards" style="border-color: rgb(251 68 0 / 50%);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="85.644" height="75.049" viewBox="0 0 85.644 75.049" class="icon-report bg-main">
                        <g id="Group_178" data-name="Group 178" transform="translate(-448.032 56.166)">
                          <g id="Group_319" data-name="Group 319" transform="translate(448.818 -55.416)">
                            <g id="Group_176" data-name="Group 176" transform="translate(0 0)">
                              <path id="Path_166" data-name="Path 166" d="M512.178,34.445H472.911a2.768,2.768,0,0,1-2.4-1.384L450.88-.946a2.77,2.77,0,0,1,0-2.768L470.514-37.72a2.768,2.768,0,0,1,2.4-1.384h39.267a2.766,2.766,0,0,1,2.4,1.384L534.21-3.714a2.77,2.77,0,0,1,0,2.768L514.576,33.061A2.766,2.766,0,0,1,512.178,34.445ZM474.51,28.909h36.071L528.615-2.33,510.581-33.568H474.51L456.474-2.33Z" transform="translate(-450.509 39.104)" fill="#fff" stroke="#fff" stroke-width="1.5"/>
                            </g>
                            <g id="Group_177" data-name="Group 177" transform="translate(17.687 12.156)">
                              <path id="Path_167" data-name="Path 167" d="M500.974,16.264a2.769,2.769,0,0,1-1.289-.318L487.638,9.61l-12.05,6.335a2.767,2.767,0,0,1-4.016-2.918l2.3-13.418-9.748-9.5a2.766,2.766,0,0,1-.7-2.837,2.77,2.77,0,0,1,2.235-1.884l13.471-1.958,6.025-12.206a2.769,2.769,0,0,1,2.483-1.543h0a2.767,2.767,0,0,1,2.482,1.543l6.025,12.206,13.471,1.958a2.766,2.766,0,0,1,2.235,1.884,2.768,2.768,0,0,1-.7,2.837L501.4-.391l2.235,13.035A2.771,2.771,0,0,1,501,16.264.182.182,0,0,0,500.974,16.264ZM472-9.942l6.774,6.6a2.767,2.767,0,0,1,.8,2.45l-1.6,9.324,8.374-4.4a2.778,2.778,0,0,1,2.576,0l8.374,4.4L495.7-.889a2.766,2.766,0,0,1,.8-2.45l6.774-6.6L493.908-11.3a2.773,2.773,0,0,1-2.084-1.514L487.638-21.3l-4.187,8.483a2.773,2.773,0,0,1-2.084,1.514Z" transform="translate(-463.288 30.321)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                            </g>
                          </g>
                        </g>
                      </svg>
                   <div>
                       <span>{{__('admin.assigned_instructors')}}</span>
                       <b>{{$assigned_instructors}}</b>
                   </div>
               </div>
            @endif

            @if (isset($count))
                <div class="course-cards" style="border-color: rgb(30 191 184 / 50%);">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  class="icon-report bg-third" id="bg-two" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                        <g>
                            <path d="M31.97,55.25c-1.12,0-2.24,0-3.35,0c-0.16-0.03-0.32-0.07-0.48-0.1c-1.02-0.15-2.06-0.22-3.06-0.44   c-5.29-1.14-9.78-3.71-13.43-7.7c-3.29-3.6-5.38-7.8-6.21-12.61c-0.16-0.9-0.26-1.81-0.39-2.71c0-1.12,0-2.23,0-3.35   c0.03-0.14,0.07-0.28,0.09-0.42c0.11-0.86,0.17-1.73,0.34-2.58c1.24-6.24,4.33-11.37,9.37-15.25c5.96-4.6,12.73-6.22,20.12-4.86   c6.2,1.13,11.29,4.26,15.17,9.23c4.68,6,6.33,12.81,4.95,20.28c-1.14,6.18-4.29,11.22-9.21,15.13c-3.3,2.62-7.03,4.3-11.19,5.01   C33.78,55.01,32.87,55.12,31.97,55.25z M51.39,29.99C51.38,18.41,41.86,8.91,30.29,8.91C18.71,8.91,9.2,18.42,9.19,30   c-0.01,11.59,9.55,21.14,21.13,21.11C41.91,51.08,51.41,41.56,51.39,29.99z"/>
                            <path d="M26.32,35.36c0.13-0.19,0.22-0.37,0.36-0.51c4.77-4.78,9.55-9.55,14.32-14.33c0.55-0.55,1.16-0.9,1.97-0.78   c0.78,0.11,1.33,0.54,1.63,1.26c0.31,0.72,0.2,1.41-0.27,2.04c-0.14,0.18-0.3,0.35-0.46,0.51C38.57,28.84,33.28,34.12,28,39.4   c-1.23,1.23-2.31,1.23-3.53,0.02c-2.64-2.64-5.28-5.27-7.91-7.91c-1.08-1.09-0.93-2.69,0.32-3.39c0.78-0.44,1.54-0.38,2.26,0.14   c0.19,0.13,0.35,0.3,0.51,0.46c2.06,2.05,4.11,4.11,6.17,6.17C25.95,35.01,26.08,35.13,26.32,35.36z"/>
                        </g>
                    </svg>
                    <div>

                        <span>{{__('admin.completed_learners')}}</span>
                        <b>{{$completed_learners}}</b>

                    </div>
                </div>
            @endif

            </div>
        </div>
    </div>
</div>
