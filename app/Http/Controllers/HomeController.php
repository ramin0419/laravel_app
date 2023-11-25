<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use Stripe;
use Session;
use App\Models\Comment;
use App\Models\Reply;


class HomeController extends Controller
{
    public function index()
    {
        $product = product::paginate(3);
        $comment = comment::orderby('id', 'desc')->get();
        $reply = reply::all();
        return view('home.userpage', compact('product', 'comment', 'reply'));
    }
    public function redirect()
    {
        $userType = Auth::user()->userType;
        if ($userType == '1') {
            $total_product = product::all()->count();
            $total_order = order::all()->count();
            $total_user = user::all()->count();
            $order = order::all();
            $total_revenue = 0;
            foreach ($order as $order) {
                $total_revenue = $total_revenue + $order->price;
            }
            $total_delivered = order::where('delivery_status', '=', 'delivered')->get()->count();
            $total_processing = order::where('delivery_status', '=', 'processing')->get()->count();
            return view('admin.home', compact('total_product', 'total_order', 'total_user', 'total_delivered', 'total_processing', 'total_revenue'));
        } else {
            $product = product::paginate(3);
            $comment = comment::orderby('id', 'desc')->get();
            $reply = reply::all();
            return view('home.userpage', compact('product', 'comment', 'reply'));
        }
    }
    public function product_details($id)
    {
        $product = product::find($id);
        return view('home.product_details', compact('product'));
    }
    public function add_cart(Request $request, $id)
    {


        $user = Auth::user();
        $userid = $user->id;
        $product = product::find($id);
        $product_exist_id = cart::where('Product_id', '=', $id)->where('user_id', '=',  $userid)->get('id')->first();


        if ($product_exist_id) {

            $cart = cart::find($product_exist_id)->first();
            $quantity = $cart->quantity;
            $cart->quantity = $quantity + $request->quantity;
            if ($product->discount_price != null) {
                $cart->price = $product->price * $cart->quantity;
            } else {

                $cart->price = $product->price * $cart->quantity;
            }

            $cart->save();


            return redirect()->back();
        } else {
            $cart = new cart();
            $cart->name = $user->name;
            $cart->email = $user->email;
            $cart->phone = $user->phone;
            $cart->address = $user->address;
            $cart->user_id = $user->id;

            $cart->product_title = $product->title;

            if ($product->discount_price != null) {
                $cart->price = $product->price * $request->quantity;
            } else {

                $cart->price = $product->price * $request->quantity;
            }
            $cart->image = $product->image;
            $cart->Product_id = $product->id;



            $cart->quantity = $request->quantity;

            $cart->save();
            return redirect()->back()->with('message', 'Product Added Successfully');
        }
    }

    public function show_cart()
    {


        if (Auth::id()) {
            $id = Auth::user()->id;
            $cart = cart::where('user_id', '=', $id)->get();
            return view('home.showcart', compact('cart'));
        } else {
            return redirect('login');
        }
    }
    public function remove_cart($id)
    {
        $cart = cart::find($id);
        $cart->delete();
        return redirect()->back();
    }
    public function cash_order()
    {
        $user = Auth::user();
        $userid = $user->id;
        $data = cart::where('user_id', '=', $userid)->get();

        foreach ($data as $data) {
            $order = new order;
            $order->name = $data->name;
            $order->email = $data->email;
            $order->phone = $data->phone;
            $order->address = $data->address;
            $order->user_id = $data->user_id;
            $order->product_title = $data->Product_title;
            $order->price = $data->price;
            $order->quantity = $data->quantity;
            $order->image = $data->image;
            $order->product_id = $data->product_id;

            $order->payment_status = 'cash on delivery';
            $order->delivery_status = 'processing';
            $order->save();

            $cart_id = $data->id;
            $cart = cart::find($cart_id);
            $cart->delete();
        }
        return redirect()->back()->with('message', 'We have Received your Order.
        We will connect with you Soon...');
    }

    public function stripe($totalprice)
    {
        return view('home.stripe', compact('totalprice'));
    }


    public function stripePost(Request $request, $totalprice)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        Stripe\Charge::create([
            "amount" => $totalprice * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Thanks for payment"
        ]);



        return redirect()->back()->with('success', 'Payment Successful');
    }

    public function show_order()
    {
        if (Auth::id()) {
            $user = Auth::user();
            $userid = $user->id;
            $order = order::where('user_id', '=', $userid)->get();
            return view('home.order', compact('order'));
        } else {
            return redirect('login');
        }
    }

    public function add_comment(Request $request)
    {
        if (Auth::id()) {
            $comment = new comment;
            $comment->name = Auth::user()->name;
            $comment->user_id = Auth::user()->id;
            $comment->comment = $request->comment;
            $comment->save();
            return redirect()->back();
        } else {
            return redirect('login');
        }
    }

    public function add_reply(Request $request)
    {
        if (Auth::id()) {
            $reply = new reply;
            $reply->name = Auth::user()->name;
            $reply->comment_id = $request->commentId;
            $reply->reply = $request->reply;
            $reply->save();
            return redirect()->back();
        } else {
            return redirect('login');
        }
    }

    public function search_product(Request $request)
    {

        $search_text = $request->search;
        $product = product::where('title', 'LIKE', "%$search_text%")->orWhere('catagory', 'LIKE', "$search_text")->paginate(3);
        $comment = comment::orderby('id', 'desc')->get();
        $reply = reply::all();
        return view('home.all_product', compact('product', 'reply', 'comment'));
    }
    public function product()
    {
        $product = product::paginate(3);
        $comment = comment::orderby('id', 'desc')->get();
        $reply = reply::all();
        return view('home.all_product', compact('product', 'comment', 'reply'));
    }

    public function product_search(Request $request)
    {

        $search_text = $request->search;
        $product = product::where('title', 'LIKE', "%$search_text%")->orWhere('catagory', 'LIKE', "$search_text")->paginate(3);
        $comment = comment::orderby('id', 'desc')->get();
        $reply = reply::all();
        return view('home.userpage', compact('product', 'reply', 'comment'));
    }
};
