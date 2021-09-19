@extends(ADMIN.'.general.index')

@section('table')

<style>
    td {
        white-space: unset !important
    }
</style>
    <div class="card questions-table">
            <div class="card-header">
                <h4 class="text-center">Watson CIPD Enrollment Form</h4>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered table-hover table-condensed table-responsive mb-2" cellspacing="0">

                    @forelse($questions as $index => $question)
                        <?php
                            $question_id = 'q_'.$question->question_id??0;
                            $answer = $question->answer??'';
                            if (str_contains($answer, 'q_')) {
                                $answer = 'Yes';
                            }
                        ?>

                        @if($index == 0)
                            <tr style="width: 50%">
                                <td class="title">Name</td>
                                <td class="value">{{json_decode($question->name)->en??$question->name}}</td>
                            </tr>
                            <tr>
                                <td class="title">Email</td>
                                <td class="value">{{$question->email??null}}</td>
                            </tr>
                            <tr>
                                <td class="title">Mobile</td>
                                <td class="value">{{$question->mobile??null}}</td>
                            </tr>
                        @endif


                        <tr>
                            <td class="title" style="width: 50%">
                                <div style="word-break: break-word;">
                                    {{json_decode($question->question)->en??$question->question}}
                                </div>
                            </td>
                            <td class="value">
                                <div style="">
                                    @if (str_contains($answer, '.pdf') || str_contains($answer, '.docx') || str_contains($answer, '.doc'))
                                        <a href="{{CustomAsset('upload/cipd/exams/'.$answer)}}" download=""><i class="fas fa-download"></i> Download CV</a>
                                    @else
                                        {{$answer??null}}
                                    @endif

                                </div>
                            </td>
                        </tr>

                    @empty
                        Trainee doen't have any course in this order.
                    @endforelse
                </table>
            </div>
    </div>

@endsection
