<div class="row mb-5">

    <div class="col-lg-12">
        <div class="card h-100 justify-content-center p-30">

            <div class="d-flex flex-column flex-sm-row flex-wrap">




                @if(isset($count))
                <div class="course-cards card-report bg-third justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" class="icon-report" id="bg-two" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                        <g>
                            <path d="M31.97,55.25c-1.12,0-2.24,0-3.35,0c-0.16-0.03-0.32-0.07-0.48-0.1c-1.02-0.15-2.06-0.22-3.06-0.44   c-5.29-1.14-9.78-3.71-13.43-7.7c-3.29-3.6-5.38-7.8-6.21-12.61c-0.16-0.9-0.26-1.81-0.39-2.71c0-1.12,0-2.23,0-3.35   c0.03-0.14,0.07-0.28,0.09-0.42c0.11-0.86,0.17-1.73,0.34-2.58c1.24-6.24,4.33-11.37,9.37-15.25c5.96-4.6,12.73-6.22,20.12-4.86   c6.2,1.13,11.29,4.26,15.17,9.23c4.68,6,6.33,12.81,4.95,20.28c-1.14,6.18-4.29,11.22-9.21,15.13c-3.3,2.62-7.03,4.3-11.19,5.01   C33.78,55.01,32.87,55.12,31.97,55.25z M51.39,29.99C51.38,18.41,41.86,8.91,30.29,8.91C18.71,8.91,9.2,18.42,9.19,30   c-0.01,11.59,9.55,21.14,21.13,21.11C41.91,51.08,51.41,41.56,51.39,29.99z"/>
                            <path d="M26.32,35.36c0.13-0.19,0.22-0.37,0.36-0.51c4.77-4.78,9.55-9.55,14.32-14.33c0.55-0.55,1.16-0.9,1.97-0.78   c0.78,0.11,1.33,0.54,1.63,1.26c0.31,0.72,0.2,1.41-0.27,2.04c-0.14,0.18-0.3,0.35-0.46,0.51C38.57,28.84,33.28,34.12,28,39.4   c-1.23,1.23-2.31,1.23-3.53,0.02c-2.64-2.64-5.28-5.27-7.91-7.91c-1.08-1.09-0.93-2.69,0.32-3.39c0.78-0.44,1.54-0.38,2.26,0.14   c0.19,0.13,0.35,0.3,0.51,0.46c2.06,2.05,4.11,4.11,6.17,6.17C25.95,35.01,26.08,35.13,26.32,35.36z"/>
                        </g>
                    </svg>
                    <div>
                        <span>{{__('admin.courses')}}</span>
                        <b>{{$count}}</b>
                    </div>

                </div>
                @endif
                @if(isset($assigned_learners))
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
                        <span>{{__('admin.assigned_learners')}}</span>
                        <b>{{$assigned_learners}}</b>
                    </div>
                </div>
                @endif

                @if(isset($completed_learners))
                <div class="course-cards card-report bg-bg-one justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" class="icon-report" id="bg-one" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                        <g>
                            <path d="M30.56,8.45c0.71,0.3,1.31,0.72,1.49,1.53c0.03,0.14,0.06,0.28,0.06,0.42c0,2.72,0.01,5.45,0,8.17   c-0.01,1.03-0.77,1.82-1.83,1.96c-0.88,0.12-1.83-0.5-2.09-1.39c-0.08-0.28-0.12-0.6-0.12-0.89c-0.01-2.38,0.03-4.76-0.02-7.14   c-0.03-1.25,0.34-2.19,1.56-2.67C29.92,8.45,30.24,8.45,30.56,8.45z"/>
                            <path d="M32.11,46.76c0,1.3,0,2.6,0,3.9c0,1.2-0.83,2.1-1.94,2.12c-1.18,0.03-2.1-0.82-2.11-2.04c-0.03-2.67-0.03-5.34,0-8.01   c0.01-1.19,0.97-2.1,2.07-2.05c1.14,0.04,1.97,0.94,1.97,2.15C32.12,44.14,32.11,45.45,32.11,46.76z"/>
                            <path d="M13.83,16.37c0.01-0.84,0.4-1.47,1.15-1.83c0.76-0.36,1.49-0.24,2.15,0.27c0.1,0.08,0.19,0.17,0.29,0.26   c1.83,1.83,3.66,3.65,5.48,5.48c0.95,0.96,0.87,2.36-0.16,3.13c-0.71,0.53-1.65,0.53-2.38,0c-0.18-0.14-0.35-0.3-0.51-0.46   c-1.77-1.76-3.53-3.53-5.3-5.29C14.11,17.5,13.81,17.01,13.83,16.37z"/>
                            <path d="M23.55,39.17c0.02,0.57-0.2,1.04-0.6,1.44c-1.88,1.89-3.76,3.79-5.66,5.66c-0.85,0.84-2.06,0.83-2.87,0.03   c-0.79-0.78-0.81-2.04,0.01-2.87c1.87-1.9,3.76-3.79,5.66-5.66c0.64-0.64,1.43-0.81,2.27-0.44C23.14,37.67,23.54,38.31,23.55,39.17   z"/>
                            <path d="M36.62,39.07c0.04-0.72,0.4-1.35,1.14-1.71c0.74-0.36,1.45-0.27,2.12,0.21c0.09,0.07,0.18,0.15,0.26,0.23   c1.86,1.86,3.72,3.71,5.57,5.57c1.05,1.06,0.8,2.68-0.48,3.3c-0.71,0.35-1.4,0.27-2.05-0.17c-0.14-0.1-0.27-0.22-0.39-0.34   c-1.82-1.81-3.63-3.63-5.45-5.45C36.9,40.29,36.62,39.79,36.62,39.07z"/>
                            <path d="M13.96,32.61c-1.4,0-2.8,0.04-4.2-0.01c-1.05-0.04-1.83-0.93-1.84-1.98c-0.01-1.04,0.78-1.92,1.82-2   c0.49-0.04,0.98-0.03,1.47-0.03c2.32,0.01,4.65-0.01,6.97,0.04c1.06,0.02,1.83,0.92,1.83,1.98c0.01,1.05-0.77,1.96-1.81,2   C16.79,32.65,15.37,32.61,13.96,32.61C13.96,32.61,13.96,32.61,13.96,32.61z"/>
                            <path d="M46.19,32.61c-0.74,0-1.47,0.04-2.21-0.01c-1.01-0.06-1.8-0.95-1.82-1.97c-0.01-1.03,0.79-1.97,1.82-2   c1.48-0.05,2.97-0.05,4.45,0c1.02,0.03,1.79,0.93,1.81,1.96c0.02,1.04-0.78,1.92-1.81,2.02c-0.1,0.01-0.2,0.02-0.3,0.03   c-0.65,0-1.3,0-1.95,0C46.19,32.63,46.19,32.62,46.19,32.61z"/>
                        </g>
                    </svg>
                    <div>

                        <span>{{__('admin.completed_learners')}}</span>
                        <b>{{$completed_learners}}</b>

                    </div>
                </div>
                @endif

                @if(isset($assigned_instructors))
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
                       <span>{{__('admin.assigned_instructors')}}</span>
                       <b>{{$assigned_instructors}}</b>
                   </div>
               </div>
               @endif


            </div>

        </div>

    </div>

</div>
