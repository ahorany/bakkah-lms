@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
<style>
.userarea-wrapper{
    background: #fafafa;
}
.card{
  height:100%;
}
.user-course .my-courses {
    border: 0.5px solid #5D5B5A;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
}
.card.my-badge {
    {{-- border: 1px solid gainsboro; --}}
    background: #fff;
    padding: 50px;
}
.line {
    width: 100%;
    background: #fb4400;
    height: 4px;
}
.left a.btn {
    background: #fb4400;
    color: #fff;
}
/*.badges{
    display:flex;
    flex-direction:row;
    flex-wrap: wrap;
}*/
.badge_icons{
    text-align: center;
}
.level h6 {
    font-size: 18px;
    font-weight: bold;
}
.level p {
    font-size: 14px;
    margin-bottom: 7px;
}
.num_level span{
    display: inline-block;
    background: transparent;
    width: 80%;
    height: 9px;
    border: 1px solid #000;
}
.num_level span.border {
    display: inline-block;
    background: #fb4400;
    width: 65%;
    height: 7.5px;
    position: absolute;
    left: 1px;
    top: 7px;
}
.num_level small {
    font-weight: bold;
}

/* calender */
.group {
  &:after {
    content: "";
    display: table;
    clear: both;
  }
}

img {
  max-width:100%;
  height:auto;
  vertical-align:baseline;
}

a {
  text-decoration:none;
}

.max(@maxWidth;
  @rules) {
    @media only screen and (max-width: @maxWidth) {
      @rules();
    }
  }

.min(@minWidth;
  @rules) {
    @media only screen and (min-width: @minWidth) {
      @rules();
    }
  }


.calendar-wrapper {
  {{-- width:360px; --}}
  margin:0 auto;
  padding:2em;
  border:1px solid @calendar-border;
  border-radius:5px;
  background:@calendar-bg;

}
table {
  clear:both;
  width:100%;
  border:1px solid @calendar-border;
  border-radius:3px;
  border-collapse:collapse;
  color:@calendar-color;
}
td {
  height: 40px;
  width: 40px;
  text-align:center;
  vertical-align:middle;
  border-right:1px solid @calendar-border;
  border-top:1px solid @calendar-border;
  width:100% / 7;
}
td.not-current {
  color:@calendar-fade-color;;
}
td.normal {}
td.today {
  font-weight:700;
  color:@calendar-standout;
  font-size:1.5em;
}
thead td {
  border:none;
  color:@calendar-standout;
  text-transform:uppercase;
  font-size:1.5em;
}
#btnPrev {
  float:left;
  margin-bottom:20px;
  &:before {
    content:'\f104';
    font-family:FontAwesome;
    padding-right:4px;
  }
}
#btnNext {
  float:right;
  margin-bottom:20px;
  &:after {
    content:'\f105';
    font-family:FontAwesome;
    padding-left:4px;
  }
}
#btnPrev, #btnNext {
  background:transparent;
  border:none;
  outline:none;
  font-size:1em;
  color:@calendar-fade-color;
  cursor:pointer;
  font-family:"Roboto Condensed", sans-serif;
  text-transform:uppercase;
  transition:all 0.3s ease;
  &:hover {
    color:@calendar-standout;
    font-weight:bold;
  }
}
button#btnPrev{
      position:absolute;
      left: 16%;
      top: 7.6%;
  }
  button#btnNext{
      position:absolute;
      right: 16%;
      top: 7.6%;
  }

  td.today {
    color: #fff;
    background: #fb4400;
    border-radius: 10px;
}

thead td {
    font-weight: bold;
}
/* end calender */

.level {
    padding: 0 20px;
}
.statistics {
    justify-content: center;
    background: #fb4400;
    color: #fff;
    height: 115px;
    border-radius: 15px;
    align-items: center;
}

video{
  width: 100%;
}
label.top {
    font-size: 15px;
}
label.bottom {
    font-size: 13px;
    color: gray;
}

/********************************/

text.highcharts-credits ,
g.highcharts-legend.highcharts-no-tooltip ,
g.highcharts-axis-labels.highcharts-yaxis-labels ,
text.highcharts-title ,
g.highcharts-axis.highcharts-yaxis ,
{
    display: none !important;
}

#divCal tr {
    height: 40px !important;
}
.navbar-brand img {
    width: 100%;
    height: 83px;
}
.home .top a {
    color: #000;
}

</style>
    <?php
        $url = '';
        if(auth()->user()->upload) {
            $url = auth()->user()->upload->file;
            $url = CustomAsset('upload/full/'. $url);
        }else {
            $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
        }
    ?>
    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10 home">
                <div class="main-user-content pb-5 px-5">
                    <div class="p-5 user-course">
                        <div class="row mx-0 my-courses">
                            <div class="col-md-8 col-12 col-lg-8">
                                <div class="left py-5">
                                    <h2 class="mb-3">{{auth()->user()->trans_name}}</h2>
                                    <p>{{auth()->user()->bio}}</p>
                                    {{-- <a href="#" class="btn">Resume Course</a> --}}
                                </div>
                            </div>
                            <div class="col-md-4 col-12 col-lg-4">
                                <div class="image">
                                    <img class="img-fluid" src="{{CustomAsset('/images/group_306.png')}}" alt="Card image cap">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 pb-5 user-course">
                        <div class="row mx-0 my-courses">
                            <div class="col-md-12 col-12 col-lg-12">
                                <h4 class="mb-0" style="font-weight:bold;">
                                    {{ __('education.Course Overview') }}
                                </h4>
                            </div>
                            @foreach($courses->courses as $course)
                                <div class="col-12 col-md-4 col-lg-3 my-2 p-4">
                                    <div class="card p-4" style="width: 100%; border-radius: 10px; border: 1px solid #f2f2f2">
                                        @isset($course->upload->file)
                                        <img class="card-img-top" src="{{CustomAsset('upload/thumb200/'.$course->upload->file)}}" alt="Card image cap">
                                        {{-- @else
                                        <img class="card-img-top" src="{{CustomAsset('images/bakkah.png')}}" alt="Card image cap"> --}}
                                        @endisset
                                        <div class="card-body text-center p-0">
                                            <h3 class="card-title mb-2" style="font-weight: 700;"><a style="color: #000;" href="{{CustomRoute('user.course_details',$course->id)}}">{{$course->trans_title  . ($course->training_option ?  '-' . $course->training_option->trans_name : '') }}</a></h3>
                                            <div class="progress" style="height:5px;">
                                                <div class="progress-bar" role="progressbar" style="background: #fb4400; width: {{$course->pivot->progress??0}}%;" aria-valuenow="{{$course->pivot->progress}}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="num m-0 mt-1" style="color:gray;">{{$course->pivot->progress??0}}%</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <dic class="details user-course">
                        <div class="row m-0 mb-4">
                            <div class="col-md-6 col-12 col-lg-6 pr-0">
                                <div class="row m-0">
                                    <div class="col-md-12 col-12 col-lg-12 pr-0">
                                        <div class="pl-3 pb-4 user-badge">
                                            <div class="card my-badge p-4 my-courses">
                                                <h4 class="mb-3" style="font-weight:bold;">
                                                    {{ __('education.Badges') }}
                                                </h4>
                                                <div class="badges row m-0">
                                                    <div class="badge_icons col-md-2 col-lg-2 col-3 mb-4">
                                                        <img class="img-fluid" src="{{CustomAsset('/images/lms1.png')}}" alt="Card image cap">
                                                    </div>
                                                    <div class="badge_icons col-md-2 col-lg-2 col-3 mb-4">
                                                        <img class="img-fluid" src="{{CustomAsset('/images/lms2.png')}}" alt="Card image cap"></div>
                                                    <div class="badge_icons col-md-2 col-lg-2 col-3 mb-4">
                                                        <img class="img-fluid" src="{{CustomAsset('/images/lms3.png')}}" alt="Card image cap">
                                                    </div>
                                                    <div class="badge_icons col-md-2 col-lg-2 col-3 mb-4">
                                                        <img class="img-fluid" src="{{CustomAsset('/images/lms4.png')}}" alt="Card image cap">
                                                    </div>
                                                    <div class="badge_icons col-md-2 col-lg-2 col-3 mb-4">
                                                        <img class="img-fluid" src="{{CustomAsset('/images/lms4.png')}}" alt="Card image cap">
                                                    </div>
                                                    <div class="badge_icons col-md-2 col-lg-2 col-3 mb-4">
                                                        <img class="img-fluid" src="{{CustomAsset('/images/lms4.png')}}" alt="Card image cap">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 col-lg-12 pr-0">
                                        <div class="pl-3 user-badge">
                                            <div class="my-courses">
                                                <div class="level d-flex">
                                                    <img class="img-fluid" src="{{CustomAsset('/images/group_237.png')}}" alt="Card image cap">
                                                    <div class="p-4 pr-0">
                                                        <h6>Awards Level</h6>
                                                        <p>Congratulations! you are at 82.</p>
                                                        <div class="num_level position-relative">
                                                            <div class="progress" style="height:5px;">
                                                              <div class="progress-bar" role="progressbar" style="background: #fb4400; width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                                          </div>
                                                          <small class="num m-0 mt-1" style="color:gray;">82/90</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6 col-12 col-lg-6">
                                <div class="px-4 user-badge">
                                    <div class="my-courses">
                                        <div class="calendar-wrapper py-0">
                                            <button id="btnPrev" type="button"><i class="fas fa-chevron-left"></i></button>
                                            <button id="btnNext" type="button"><i class="fas fa-chevron-right"></i></button>
                                            <div id="divCal"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0">
                          <div class="col-md-6 col-12 col-lg-6 pr-0 mb-4">
                            <div class="pl-4 pr-0 user-badge">
                                <div class="my-courses">
                                    <div id="container" style="height: 400px; width: 100%"></div>
                                </div>
                              </div>
                          </div>

                          <div class="col-md-6 col-12 col-lg-6 mb-2" style="display: flex;">
                              <div class="px-4 user-badge">
                                  <div class="row">

                                      <div class="col-md-6 col-12 col-lg-6 mb-3">
                                        <div class="statistics d-flex">
                                          <img style="width:15%;" class="img-fluid" src="{{CustomAsset('/images/icon1.png')}}" alt="Card image cap">
                                          <div class="pl-3 text-center">
                                            <p class="m-0">Course Completed</p>
                                            <p class="m-0" style="font-weight: bold; font-size: 37px;">{{ isset($complete_courses[1]) ? str_pad($complete_courses[1]->courses_count, 2, '0', STR_PAD_LEFT) : 0 }}</p>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6 col-12 col-lg-6 mb-3">
                                        <div class="statistics d-flex" style="background:#1EBFB8;">
                                          <img style="width:15%;" class="img-fluid" src="{{CustomAsset('/images/icon2.png')}}" alt="Card image cap">
                                          <div class="pl-3 text-center">
                                            <p class="m-0">Course in Progress</p>
                                            <p class="m-0" style="font-weight: bold; font-size: 37px;">{{isset($complete_courses[0]) ? str_pad($complete_courses[0]->courses_count, 2, '0', STR_PAD_LEFT) : 0}}</p>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6 col-12 col-lg-6 mb-3">
                                        <div class="statistics d-flex"  style="background:#FD9A18;">
                                          <img style="width:15%;" class="img-fluid" src="{{CustomAsset('/images/icon3.png')}}" alt="Card image cap">
                                          <div class="pl-3 text-center">
                                            <p class="m-0">Your Certification</p>
                                            <p class="m-0" style="font-weight: bold; font-size: 37px;">02</p>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6 col-12 col-lg-6 mb-3">
                                        <div class="statistics d-flex"  style="background:#ADADAD;">
                                          <img style="width:15%;" class="img-fluid" src="{{CustomAsset('/images/icon4.png')}}" alt="Card image cap">
                                          <div class="pl-3 text-center">
                                            <p class="m-0">Your Points</p>
                                            <p class="m-0" style="font-weight: bold; font-size: 37px;">35</p>
                                          </div>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="col-md-6 col-12 col-lg-6 pr-0 mb-2">
                              <div class="pl-3 pb-4 user-badge">
                                  <div class="card my-badge p-5 my-courses">
                                      <h4 class="mb-3" style="font-weight:bold;">
                                          {{ __('education.Last Video View') }}
                                      </h4>
                                      <div class="video">
                                          @if($last_video)
                                              <video controls>
                                                  <source  src="{{CustomAsset('upload/files/videos/'.$last_video->file)}}">
                                              </video>
                                          @endif
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="col-md-6 col-12 col-lg-6 mb-2">
                              <div class="px-4 user-badge">
                                  <div class="card my-badge px-5 pt-5 pb-0 my-courses">
                                      <h4 class="mb-3" style="font-weight:bold;">
                                          {{ __('education.Next Video') }}
                                      </h4>
                                      <div class="videos_list pt-3">
                                          @foreach($next_videos as $next_video)

                                             <div class="row m-0 mb-3 pb-2"  @if(!$loop->last)style="border-bottom: 1px solid #707070;" @endif>
                                                  <div class="col-md-1 col-1 col-lg-1 px-0">
                                                    <img style="width:100%;" class="img-fluid" src="{{CustomAsset('/images/play_button.png')}}" alt="Card image cap">
                                                   </div>
                                            <div class="col-md-9 col-9 col-lg-9 text-left">
                                              <label class="m-0 top"><a href="{{CustomRoute('user.course_preview',$next_video->id)}}">{{$next_video->title}}</a></label>
{{--                                              <label class="m-0 bottom">Assess your Knowledge - Pre-Learning</label>--}}
                                            </div>
{{--                                            <div class="col-md-2 col-2 col-lg-2 text-right">--}}
{{--                                              <label class="py-3 m-0">6:24</label>--}}
{{--                                            </div>--}}
                                          </div>
                                          @endforeach

                                      </div>
                                  </div>
                              </div>
                          </div>

                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.src.js"></script>

    <script>


/************* calender **************/

var Cal = function(divId) {

//Store div id
this.divId = divId;

// Days of week, starting on Sunday
this.DaysOfWeek = [
  'Sun',
  'Mon',
  'Tue',
  'Wed',
  'Thu',
  'Fri',
  'Sat'
];

// Months, stating on January
this.Months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];

// Set the current month, year
var d = new Date();

this.currMonth = d.getMonth();
this.currYear = d.getFullYear();
this.currDay = d.getDate();

};

// Goes to next month
Cal.prototype.nextMonth = function() {
if ( this.currMonth == 11 ) {
  this.currMonth = 0;
  this.currYear = this.currYear + 1;
}
else {
  this.currMonth = this.currMonth + 1;
}
this.showcurr();
};

// Goes to previous month
Cal.prototype.previousMonth = function() {
if ( this.currMonth == 0 ) {
  this.currMonth = 11;
  this.currYear = this.currYear - 1;
}
else {
  this.currMonth = this.currMonth - 1;
}
this.showcurr();
};

// Show current month
Cal.prototype.showcurr = function() {
this.showMonth(this.currYear, this.currMonth);
};

// Show month (year, month)
Cal.prototype.showMonth = function(y, m) {

var d = new Date()
// First day of the week in the selected month
, firstDayOfMonth = new Date(y, m, 1).getDay()
// Last day of the selected month
, lastDateOfMonth =  new Date(y, m+1, 0).getDate()
// Last day of the previous month
, lastDayOfLastMonth = m == 0 ? new Date(y-1, 11, 0).getDate() : new Date(y, m, 0).getDate();


var html = '<table>';

// Write selected month and year
html += '<thead><tr>';
html += '<td colspan="7">' + this.Months[m] + ' ' + y + '</td>';
html += '</tr></thead>';


// Write the header of the days of the week
html += '<tr class="days">';
for(var i=0; i < this.DaysOfWeek.length;i++) {
  html += '<td>' + this.DaysOfWeek[i] + '</td>';
}
html += '</tr>';

// Write the days
var i=1;
do {

  var dow = new Date(y, m, i).getDay();

  // If Sunday, start new row
  if ( dow == 0 ) {
    html += '<tr>';
  }
  // If not Sunday but first day of the month
  // it will write the last days from the previous month
  else if ( i == 1 ) {
    html += '<tr>';
    var k = lastDayOfLastMonth - firstDayOfMonth+1;
    for(var j=0; j < firstDayOfMonth; j++) {
      html += '<td class="not-current">' + k + '</td>';
      k++;
    }
  }

  // Write the current day in the loop
  var chk = new Date();
  var chkY = chk.getFullYear();
  var chkM = chk.getMonth();
  if (chkY == this.currYear && chkM == this.currMonth && i == this.currDay) {
    html += '<td class="today">' + i + '</td>';
  } else {
    html += '<td class="normal">' + i + '</td>';
  }
  // If Saturday, closes the row
  if ( dow == 6 ) {
    html += '</tr>';
  }
  // If not Saturday, but last day of the selected month
  // it will write the next few days from the next month
  else if ( i == lastDateOfMonth ) {
    var k=1;
    for(dow; dow < 6; dow++) {
      html += '<td class="not-current">' + k + '</td>';
      k++;
    }
  }

  i++;
}while(i <= lastDateOfMonth);

// Closes table
html += '</table>';

// Write HTML to the div
document.getElementById(this.divId).innerHTML = html;
};

// On Load of the window
window.onload = function() {

// Start calendar
var c = new Cal("divCal");
c.showcurr();

// Bind next and previous button clicks
getId('btnNext').onclick = function() {
  c.nextMonth();
};
getId('btnPrev').onclick = function() {
  c.previousMonth();
};
}

// Get element by id
function getId(id) {
return document.getElementById(id);
}


/************* line chart **************/

var chart = new Highcharts.Chart({
  chart: {
    renderTo: 'container',
    marginBottom: 80
  },
  xAxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    labels: {
      rotation: 90
    }
  },

  series: [{
    data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
  }]
});

    </script>

@endsection
