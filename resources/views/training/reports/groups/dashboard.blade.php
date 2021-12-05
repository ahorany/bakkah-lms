<div class="row mb-5">

    <div class="col-lg-12">
        <div class="card h-100 justify-content-center p-30">

            <div class="d-flex flex-column flex-sm-row flex-wrap">

                @if(isset($count))
                <div class="course-cards card-report bg-five justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="71.3" height="71.3" viewBox="0 0 71.3 71.3">
                        <path id="Path_164" data-name="Path 164" d="M254.387,629.475h-8.913v8.912a8.91,8.91,0,0,1-8.912,8.913h-35.65A8.91,8.91,0,0,1,192,638.387v-35.65a8.91,8.91,0,0,1,8.913-8.912h8.913v-8.913A8.91,8.91,0,0,1,218.737,576h35.65a8.91,8.91,0,0,1,8.912,8.912v35.65a8.91,8.91,0,0,1-8.912,8.913Zm-53.475,8.912h35.65v-35.65h-35.65v35.65Zm53.475-53.475h-35.65v8.913h17.825a8.91,8.91,0,0,1,8.912,8.912v17.825h8.913v-35.65Z" transform="translate(-192 -576)" fill="#fff" fill-rule="evenodd"/>
                    </svg>
                    <div>
                        <span>{{__('admin.groups')}}</span>
                        <b>{{$count}}</b>

                    </div>
                </div>
                @endif


                @if(isset($assigned_users))
                <div class="course-cards card-report bg-four justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="85.644" height="75.049" viewBox="0 0 85.644 75.049">
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
                        <span>{{__('admin.assigned_users')}}</span>
                        <b>{{$assigned_users}}</b>
                    </div>

                </div>
                @endif
                @if(isset($assigned_courses))
                 <div class="course-cards card-report bg-four justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="85.644" height="75.049" viewBox="0 0 85.644 75.049">
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
                        <span>{{__('admin.assigned_courses')}}</span>
                        <b>{{$assigned_courses}}</b>
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>

</div>
