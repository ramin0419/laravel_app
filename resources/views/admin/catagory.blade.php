<x-app-layout>

</x-app-layout>
<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.css')
    <style type="text/css">
        .div_center {
            text-align: center;
            padding-top: 40px;
        }

        .h2_font {
            font-style: 40px;
            padding-bottom: 40px;
        }

        .input_color {

            color: black;
        }

        .center {
            margin: auto;
            width: 50%;
            text-align: center;
            margin-top: 30px;
            border: 3px solid white;

        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_sidebar.html -->
        @include('admin.sidebar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
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
                        <h2 class="h2_font">
                            Add Catagory
                            <form action="{{url('add_catagory')}}" method="POST">
                                @csrf
                                <input type="text" class="input_color" name="catagory" placeholder="write catagory name">
                                <input type="submit" class="btn btn-primary" name="submit" value="Add Catagory">

                            </form>
                        </h2>
                    </div>
                    <table class="center">

                        <tr>

                            <td>Catagory Name</td>
                            <td>Action</td>

                        </tr>

                        @foreach($data as $data)
                        <tr>
                            <td>{{$data->catagory_name}}</td>
                            <td>
                                <a onclick="return confirm ('Are You Sure To Delete This ?')" class="btn btn-danger" href="{{url('delete_catagory',$data->id)}}"> Delete</a>
                            </td>
                        </tr>
                        @endforeach

                    </table>
                </div>
            </div>


            <!-- container-scroller -->
            <!-- plugins:js -->

            <!-- End custom js for this page -->
</body>

</html>