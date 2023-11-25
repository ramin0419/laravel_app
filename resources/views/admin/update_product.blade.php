<!DOCTYPE html>
<html lang="en">

<head>

    @include('admin.css')

    <style type="text/css">
        .div_center {
            text-align: center;
            padding-top: 40px;
        }

        .font_size {
            font-size: 40px;
            padding-bottom: 40px;
        }

        .text_color {
            color: black;
            padding: 20px;
        }

        label {
            display: inline-block;
            width: 200px;
        }

        .div_design {
            padding-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_sidebar.html -->
        @include('admin.sidebar')
        <!-- partial -->
        @include('admin.header')
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">

                @if(session()->has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" arial-hidden="true">x</button>
                    {{session()->get('message')}}
                </div>
                @endif
                <div class="div_center">
                    <h1 class="font_size">Update Product</h1>

                    <form action="{{url('update_product_confirm')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="div_design">
                            <label> Product Title :</label>
                            <input class="text_color" type="text" name="title" placeholder="Write Product Title" required="" value="{{$product->title}}">
                        </div>
                        <div class="div_design">

                            <label> Product Description:</label>
                            <input class="text_color" type="text" name="description" placeholder="Write Product Description" required="" value="{{$product->description}}">
                        </div>
                        <div class="div_design">

                            <label> Product Price:</label>
                            <input class="text_color" type="number" name="price" placeholder="Write a price " required="" value="{{$product->price}}">
                        </div>
                        <div class="div_design">

                            <label>Discount Price:</label>
                            <input class="text_color" type="number" name="dis_price" placeholder="Write discount price" value="{{$product->discount_price}}">
                        </div>
                        <div class="div_design">

                            <label> Product Quantity:</label>
                            <input class="text_color" type="number" min="0" name="quantity" placeholder="Write Quantity" required="" value="{{$product->quantity}}">
                        </div>
                        <div class="div_design">

                            <label> Product Catagory:</label>
                            <select class="text_color" name="catagory" required="">
                                <option value="{{$product->catagory}}" selected="">{{$product->catagory}}</option>
                                @foreach ($catagory as $catagory)
                                <option value="{{$catagory->catagory_name}}">{{$catagory->catagory_name}} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="div_design">

                            <label> Current Product Image:</label>
                            <img style="margin:auto;" height="100" width="100" src="/product/{{$product->image}}">
                        </div>
                        <div class="div_design">

                            <label> Change Product Image:</label>
                            <input type="file" name="image" required="">
                        </div>
                        <div class="div_design">
                            <input class="btn btn-primary" value="Update Product" type="submit" name="submit">

                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- container-scroller -->
        <!-- plugins:js -->
        @include('admin.script')
        <!-- End custom js for this page -->
</body>

</html>