<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Pair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return all pair data to json format or return error message if no pair data found;
        $pair = Pair::all()->map(function($item){
            return [
                'name' => $item->name,
                'rate' => $item->rate,
                'currency_from' => $item->currency_from,
                'currency_to' => $item->currency_to,
            ];
        });
        if(!$pair) return response()->json(['error' => 'No pair data found'], 404);
        return response()->json($pair, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    // create route function to convert currency pair from one currency to another currency;
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function convert(Request $request)
    {
        // validaor request data;
        $validator = Validator::make($request->all(), [
            'from' => 'required|string|min:3|max:3',
            'to' => 'required|string|min:3|max:3',
            'amount' => 'required|numeric',
        ]);

        $from = strtoupper($request->query('from'));
        $to = strtoupper($request->query('to'));
        // field amount not required (default value is 1);
        $amount = $request->query('amount') ?? 1;

        // if validator fails return error message;
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }
        if ($from == $to) return response()->json(['error' => '`from` and `to` cannot be the same'], 400);


        [$currency_from, $currency_to] = [Currency::getBycode($from), Currency::getBycode($to)];
        if (!$currency_from || !$currency_to) return response()->json(['error' => '`from` or `to` parameters must be valid currency codes'], 404);
        $pair = Pair::getPairByCurrencies($currency_from, $currency_to);

        if ($pair == null) return response()->json(['error' => 'Pair not found'], 404);

        $rate = $pair->rate;

        // reverse rate if the request is the reverse of the pair's model;
        if ($from == $pair->currency_to->code) {
            $rate = 1 / $rate;
        }
        $result = $amount * $rate;
        return response()->json(
            [
                'from' => $from,
                'to' => $to,
                'amount' => $amount,
                'base_rate' => $pair->rate, // base rate is the rate of the pair's model;
                'rate' => $rate,
                'result' => $result
            ],
            200
        );
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pair  $pair
     * @return \Illuminate\Http\Response
     */
    public function show(string $name)
    {
        $pair = Pair::where('name', $name)->get();
        if ($pair->isEmpty()) {
            return response()->json(['error' => 'Pair not found'], 404);
        }
        return response()->json($pair, 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pair  $pair
     * @return \Illuminate\Http\Response
     */
    public function edit(Pair $pair)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pair  $pair
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pair $pair)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pair  $pair
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pair $pair)
    {
        //
    }
}
