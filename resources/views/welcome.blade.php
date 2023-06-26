@extends('layout.main')
@section('main')
    <div class="row m-t-10 m-b-10">
        <!-- Searching -->
        <div class="row m-t-10 m-b-10">
            <div class="col-sm-6">
                <form role="form" method="GET" action="{{ route('home.index') }}">
                    <div class="form-group contact-search m-b-0">
                        <input value="{{ $request->get('term') }}" type="text" name="term" id="search"
                            class="form-control product-search" placeholder="Search here...">
                        <button type="submit" class="btn btn-white"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-md-4">
                                <h5><b>Filtering Data Rating</b></h5>
                                <select name="rating" class="form-control select2 select2-hidden-accessible" tabindex="-1"
                                    aria-hidden="true">
                                    <option value="">.::Pilih::.</option>
                                    <option value="1" {{ $request->get('rating') == 1 ? 'selected' : '' }}>Bintang 1
                                    </option>
                                    <option value="2" {{ $request->get('rating') == 2 ? 'selected' : '' }}>Bintang 2
                                    </option>
                                    <option value="3" {{ $request->get('rating') == 3 ? 'selected' : '' }}>Bintang 3
                                    </option>
                                    <option value="4" {{ $request->get('rating') == 4 ? 'selected' : '' }}>Bintang 4
                                    </option>
                                    <option value="5" {{ $request->get('rating') == 5 ? 'selected' : '' }}>Bintang 5
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <h5><b>Filtering Data Marketplace</b></h5>
                                <select name="marketplace" class="form-control select2 select2-hidden-accessible"
                                    tabindex="-1" aria-hidden="true">
                                    <option value="">.::Pilih::.</option>
                                    <option value="Shopee" {{ $request->get('marketplace') == 'Shopee' ? 'selected' : '' }}>
                                        Shopee</option>
                                    <option value="Lazada" {{ $request->get('marketplace') == 'Lazada' ? 'selected' : '' }}>
                                        Lazada</option>
                                    <!-- <option value="Bukalapak"
                                        {{ $request->get('marketplace') == 'Bukalapak' ? 'selected' : '' }}>Bukalapak
                                    </option> -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <h5><b>Filtering Data Kategori</b></h5>
                                <select name="kategori" class="form-control select2 select2-hidden-accessible"
                                    tabindex="-1" aria-hidden="true">
                                    <option value="">.::Pilih::.</option>
                                    <option value="elektronik"
                                        {{ $request->get('kategori') == 'elektronik' ? 'selected' : '' }}>Elektronik
                                    </option>
                                    <option value="kosmetik"
                                        {{ $request->get('kategori') == 'kosmetik' ? 'selected' : '' }}>Kosmetik</option>
                                    <option value="pakaian" {{ $request->get('kategori') == 'pakaian' ? 'selected' : '' }}>
                                        Pakaian</option>
                                    <option value="atk" {{ $request->get('kategori') == 'atk' ? 'selected' : '' }}>ATK
                                    </option>
                                    <option value="kesehatan"
                                        {{ $request->get('kategori') == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-sm-6">
                <div class="h5 m-0 text-right">
                    <label class="vertical-middle m-r-10">Sort By:</label>
                    <div class="btn-group vertical-middle">
                        <a href="{{ request()->fullUrlWithQuery(['sortby' => 'harga']) }}"
                            class="btn btn-default btn-md waves-effect active">Harga</a>
                        <a href="{{ request()->fullUrlWithQuery(['sortby' => 'similarities']) }}"
                            class="btn btn-default btn-md waves-effect">Similarity
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Content -->
    <div class="row">
        <div class="m-b-15">
            <!-- Filter Data -->
            <?php if(count($data) == 0) :?>
            <div class="container">
                <div class="wrapper-page">
                    <div class="ex-page-content text-center">
                        <div class="text-error">
                            <span class="text-primary">4</span><i class="ti-face-sad text-pink"></i><span
                                class="text-info">4</span>
                        </div>
                        <h2>Who0ps! Data not found</h2>
                        <br>
                    </div>
                </div>
            </div>
            <?php endif?>

            <?php foreach($data as $key): ?>
            <div class="col-sm-6 col-lg-3 col-md-4 mobiles">
                <div class="product-list-box thumb">
                    <a href="javascript:void(0);" class="image-popup" title="Screenshot-1">
                        <img src="{{ $key->kategory->image }}" class="thumb-img" alt="work-thumbnail">
                    </a>
                    <div class="price-tag">
                        <?= $key->rating ?>
                    </div>
                    <div class="detail">
                        <h4 class="m-t-0"><a class="text-dark"
                                title="{{ $key->nama_produk }}"><?= $key->nama_produk ?></a></h4>
                        <?php
                        $rupiah = $key->harga;
                        echo 'Rp ', number_format($rupiah, 0, '.', '.') . '<br>';
                        ?>
                        <div class="rating">
                            <ul class="list-inline">
                                <?php for($i = 1; $i <= (int)$key->rating; $i++) : ?>
                                <li><a class="fa fa-star" href=""></a></li>
                                <?php endfor ?>
                                <?php $sisa = 5 - (int) $key->rating; ?>
                                <?php for($i = 1; $i <= $sisa; $i++) : ?>
                                <li><a class="fa fa-star-o" href=""></a></li>
                                <?php endfor ?>
                            </ul>
                        </div>
                        <?php
                        $toko = $key->nama_toko;
                        if (strlen($key->nama_toko) > 20) {
                            $toko = substr($key->nama_toko, 0, 15) . '...';
                        }
                        ?>
                        <h5 class="m-0"> <span class="text-muted"> <?= $toko ?> </span></h5>
                        <h5 class="m-0">
                            <span class="text-muted">
                                <?php
                                $pcs = $key->terjual;
                                echo 'Terjual ', number_format($pcs, 0, '.', '.'), ' Pcs' . '<br>';
                                ?>
                            </span><br>
                        </h5>

                        @if ($similarities)
                            <h5 class="m-0"> <span class="text-muted">Cosine Similarity:
                                    {{ $similarities[$key->id]['cosine_similarity'] }}
                                </span></h5>
                        @endif
                        <h5 class="m-t-0"><a class="text-dark"> <?= $key->marketpalce ?></a> </h5>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <!-- Pagination -->
    <div style="text-align:center">
        @if ($link)
            {!! $link->appends(Request::all())->links() !!}
        @else
            {!! $data->appends(Request::all())->links() !!}
        @endif
    </div>
@endsection
