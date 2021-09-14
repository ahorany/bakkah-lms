@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(94)??null])
@endsection

@section('content')
@include(FRONT.'.education.Html.page-header', ['title'=>__('education.Cart')])
<div class="main-content py-5">
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-12">
                <table class="table table-hover">
                <thead>
                <tr>
                <th>Product</th>
                <th style="width: 5%">Quantity</th>
                <th class="text-center">Price</th>
                <th class="text-center">Total</th>
                <th> </th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="media">
                                <a class="thumbnail pull-left" href="#"> <img class="media-object" src="http://icons.iconarchive.com/icons/custom-icon-design/flatastic-2/72/product-icon.png" style="width: 72px; height: 72px;"> </a>
                                <div class="media-body mx-3">
                                    <h4 class="media-heading"><a href="#">Package</a></h4>
                                    <span>Status: </span><span class="text-warning"><strong>In Stock</strong></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="number" class="form-control" value="3">
                        </td>
                        <td class="text-center">
                            <strong>$9.99</strong>
                        </td>
                        <td class=" text-center">
                            <strong>$99.99</strong>
                        </td>
                        <td class=" text-center">
                            <button type="button" class="btn btn-primary btn-sm"><span class="fas fa-times"></span></button>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
            <div class="col-md-5  bg-light p-4">
                <table class="table m-0">
                    <tr>
                        <td><h5>Subtotal</h5></td>
                        <td class="text-right"><h5><strong>$999.99</strong></h5></td>
                        </tr>
                        {{-- <tr>
                        <td><h5>Estimated shipping</h5></td>
                        <td class="text-right"><h5><strong>$9.999.99</strong></h5></td>
                        </tr> --}}
                        <tr>
                        <td><h3>Total</h3></td>
                        <td class="text-right"><h3><strong>$9.999.99</strong></h3></td>
                        </tr>
                        <tr>
                        <td>
                        <button type="button" class="btn btn-secondary btn-block">
                        <span class="fa fa-shopping-cart"></span> Continue Shopping
                        </button></td>
                        <td>
                        <button type="button" class="btn btn-primary btn-block">
                        Checkout</span>
                        </button></td>
                        </tr>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection
