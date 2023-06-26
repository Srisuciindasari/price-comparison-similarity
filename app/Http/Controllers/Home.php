<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dataset;
use App\StopList;
use Illuminate\Support\Facades\DB;

class Home extends Controller
{
    function index(Request $request)
    {
        if (!$request->term && !$request->rating && !$request->marketplace && !$request->kategori) {
            if ($request->sortby == 'harga')
                $data = Dataset::orderBy('harga', 'desc')->paginate(20);
            else
                $data = Dataset::paginate(20);
            $similarities = $link = null;
        } else {
            $result = Dataset::nama($request->get('term'))
                ->rating($request->get('rating'))
                ->marketPlace($request->get('marketplace'))
                ->kategori($request->get('kategori'));

            if ($request->sortby == 'harga') {
                $result = $result->orderBy('harga', 'asc');
            }

            $similarities = $this->getCosineSimilarity($result->get(), $request->get('term'));
            $data = $result->paginate(20);
            $link = $data;
            $data = $data->map(function ($val) use ($similarities) {
                $val->similarities = $similarities[$val->id]['cosine_similarity'];
                return $val;
            });

            if ($request->sortby == 'similarities' || !$request->sortby) {
                $data = $data->sortByDesc('similarities');
            }
        }

        return view('welcome', compact('data', 'request', 'similarities', 'link'));
    }

    public function getCosineSimilarity($produkList, $keyword)
    {
        $token = [];
        $stop_list = array_merge(StopList::pluck('word')->toArray(), ['', ' ']);
        foreach ($produkList as $i => $dta) {
            $terms = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $dta['nama_produk']);
            $terms = explode(' ', $terms);
            foreach ($terms as $term) {
                $term = strtolower($term);
                if (!in_array($term, $stop_list)) {
                    if (!in_array($term, $token)) {
                        if (!preg_match('/\d/', $term)) {
                            $token[] = $term;
                        }
                    }
                }
            }
        }

        $keyword = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $keyword);
        $queryTerms = explode(' ', strtolower($keyword));
        $result = [];
        $tes = [];
        foreach ($token as $dta) {
            $kk = 0;
            foreach ($queryTerms as $key) {
                if ($key == $dta) $kk += 1;
            }

            $dok = $prk = [];
            $df = 0;
            foreach ($produkList as $x => $dk) {
                $produk = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $dk['nama_produk']);
                $produk = strtolower($produk);
                $prk[] = $produk;
                $dok[$dk['id']] = substr_count($produk, $dta);
                if (substr_count($produk, $dta) >= 1) $df += 1;
            }

            $Ddf = count($produkList) / $df;
            $idf = 1 + log($Ddf, 10);

            $W = ['kk' => $kk * $idf];
            foreach ($dok as $d => $D) {
                $W[$d] = $D * $idf;
            }
            $result[] = $W;
        }

        $hasil = [];
        foreach ($produkList as $x => $dk) {
            $produk = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $dk['nama_produk']);
            $tfidf = $key = [];
            foreach ($result as $res) {
                $key[] = $res['kk'];
                $tfidf[] = $res[$dk['id']];
            }

            $cosine_similarity = $this->cosineSimilarity($key, $tfidf);
            $hasil[$dk->id] = [
                'nama_produk' => $produk,
                'cosine_similarity' => number_format($cosine_similarity, 3, '.', '.')
            ];
        }
        return $hasil;
    }

    public function cosineSimilarity($vector1, $vector2)
    {
        // Menghitung dot product (jumlah perkalian elemen vektor)
        $dotProduct = 0;
        for ($i = 0; $i < count($vector1); $i++) {
            $dotProduct += $vector1[$i] * $vector2[$i];
        }

        // Menghitung panjang vektor 1
        $vector1Length = 0;
        foreach ($vector1 as $value) {
            $vector1Length += pow($value, 2);
        }
        $vector1Length = sqrt($vector1Length);


        // Menghitung panjang vektor 2
        $vector2Length = 0;
        foreach ($vector2 as $value) {
            $vector2Length += pow($value, 2);
        }
        $vector2Length = sqrt($vector2Length);

        // Menghitung cosine similarity
        $vectorLength = $vector1Length * $vector2Length;
        $cosineSimilarity = $vectorLength ? $dotProduct / $vectorLength : 0;

        return $cosineSimilarity;
    }
}
