<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Dataset extends Model
{
    protected $table='dataset';

    public function kategory()
    {
        return $this->hasOne(kategori::class, 'id', 'subkategori');
    }

    // Filter Model -> Home
    public function scopeNama($query, $name)
    {
        if (!is_null($name)) {
            $query->where('nama_produk', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function scopeRating($query, $name)
    {
        if (!is_null($name)) {
            $query->where(DB::Raw('floor(rating)'), '=', $name);
        }
        return $query;
    }

    public function scopeMarketPlace($query, $name)
    {
        if (!is_null($name)) {
            $query->where('marketpalce', '=', $name);
        }
        return $query;
    }

    public function scopeKategori($query, $name)
    {
        if (!is_null($name)) {
            $query->where('kategori', '=', $name);
        }
        return $query;
    }
}
