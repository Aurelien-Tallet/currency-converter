<?php

namespace App\Http\Controllers;

use App\Models\Conversions;
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
        $pairs = Pair::with('currency_from', 'currency_to', 'conversions')->get();


        if (!$pairs) return response()->json(['error' => 'No pair data found'], 404);
        return response()->json($pairs, 200);
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
        // validate request data
        $validator = Validator::make($request->all(), [
            'currency_id_from' => 'required|exists:currencies,id',
            'currency_id_to' => 'required|exists:currencies,id',
            'rate' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $currency_from = Currency::find($request->currency_id_from);
        $currency_to = Currency::find($request->currency_id_to);
        $pair = Pair::create(
            [
                'name' => $currency_from->name . '_' . $currency_to->name,
                'currency_id_from' => $request->currency_id_from,
                'currency_id_to' => $request->currency_id_to,
                'rate' => $request->input('rate'),
            ]
        );
        return response()->json(['success' => 'Pair created successfully'], 200);
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
        // added conversion in table 
       Conversions::create([
          'pair_id' => $pair->id,
        ]);
        return response()->json(
            [
                'from' => $currency_from,
                'to' => $currency_to,
                'amount' => $amount,
                'base_rate' => $pair->rate, // the rate of the pair's model;
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
        $validator = Validator::make($request->all(), [
            'currency_id_from' => 'integer|exists:currencies,id',
            'currency_id_to' => 'integer|exists:currencies,id',
            'rate' => 'numeric',
        ]);
        // if validator fails return error message;
        $currency_from = Currency::find($request->input('currency_id_from'));
        $currency_to = Currency::find($request->input('currency_id_to'));
        if ($validator->fails() || $currency_from->id == $currency_to->id) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }
  
        $pair->update([
            'name' => $currency_from->code . '_' . $currency_to->code,
            'currency_id_from' => $currency_from->id,
            'currency_id_to' => $currency_to->id,
            'rate' => $request->input('rate'),
        ]);
        return response()->json($pair, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pair  $pair
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pair $pair)
    {
        $pair->delete();
        return response()->json(['message' => 'Pair deleted'], 200);
    }
}
