@extends(FRONT.'.education.layouts.master-user')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(72)??null])
@endsection

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('front.education.users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-4 user-info">
                        <h2>{{ __('education.Payment Info') }}</h2>
                        <label class="chk_container mt-4">Yes please – I want to save my payment credentials informations
                            <input type="checkbox" name="mail_subscribe" value="1">
                            <span class="checkmark"></span>
                        </label>

                        <table class="tc-table table table-striped table-responsive bg-light text-center card border mt-5" id="table-5">
                            <thead class="bg-primary text-white">
                            <tr>
                                <th>Date</th>
                                <th>Order ID</th>
                                <th>Details</th>
                                <th>Price</th>
                                <th>Ammount</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span>13 Dec 2020</span> -
                                        <span>21 Dec 2020</span>
                                    </td>
                                    <td>12340</td>
                                    <td>PMP Course</td>
                                    <td>920 SAR</td>

                                    <td> 1 </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
