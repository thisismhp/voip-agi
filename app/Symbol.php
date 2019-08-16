<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
    protected $connection = "service";

    protected $fillable = ['symbolId','is_active','m_file','w_file','unit_id','fName','eName','buyPriceFormatted','sellPriceFormatted','transPriceFormatted','buysellDiff','vol','volTick','direction','transType','dsabt'];

    protected $with = ['unit'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public static function storeSym($symbols)
    {
        foreach ((array)$symbols as $symbol) {
            $item = ((array)$symbol);
            if($item['symbolId'] != 0){
                $query = self::where('symbolId',$item['symbolId']);
                if($query->count() == 0){
                    $newSymbol = new self();
                }else{
                    $newSymbol = self::find($query->get()->last()->id);
                }
                $newSymbol->symbolId = $item['symbolId'];
                $newSymbol->fName = $item['fName'];
                $newSymbol->eName = $item['eName'];
                $newSymbol->buyPriceFormatted = $item['buyPriceFormatted'];
                $newSymbol->sellPriceFormatted = $item['sellPriceFormatted'];
                $newSymbol->transPriceFormatted = $item['transPriceFormatted'];
                $newSymbol->buysellDiff = $item['buysellDiff'];
                $newSymbol->vol = $item['vol'];
                $newSymbol->volTick = $item['volTick'];
                $newSymbol->direction = $item['direction'];
                $newSymbol->transType = $item['transType'];
                $newSymbol->dsabt = $item['dsabt'];
                if($query->count() == 0) {
                    $newSymbol->save();
                }else{
                    $newSymbol->update();
                }
            }
        }
    }
}
