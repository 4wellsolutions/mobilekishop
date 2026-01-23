<form action="" method="get" class="formFilter mb-1">
    <input type="hidden" name="filter" value="true">
    <div class="row d-flex justify-content-between filter">
        <div class="col-auto">
            <div class="">
                <label>Sort By:</label>
                <div class="select-custom">
                    <select name="orderby" id="sort_filter" class="select-filter form-control">
                        <option value=""  selected="selected" {{(Request::get('orderby') == 0) ? "selected" : ''}}>Default sorting</option>
                        <option value="new" {{(Request::get('orderby') == "new") ? "selected" : ''}}>Sort by Latest</option>
                        <option value="price_asc" {{(Request::get('orderby') == "price_asc") ? "selected" : ''}}>Sort by price: low to high</option>
                        <option value="price_desc" {{(Request::get('orderby') == "price_desc") ? "selected" : ''}}>Sort by price: high to low</option>
                    </select>
                </div><!-- End .select-custom -->
            </div>
        </div>
        <div class="col-auto mt-auto">
            <div class="border rounded-circle">
                <img src="{{URL::to('/images/icons/filter.png')}}" class="img-fluid m-2" alt="filter-icon" style="cursor: pointer;" data-bs-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter" width="30" height="30">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="collapse" id="filter">
              <div class="row mt-3">
                    <div class="col-6 col-md-4">
                        <div class="filter">
                            <label class="font-weight-bold">RAM</label>
                            <select class="select-filter form-control rounded py-1" name="ram_in_gb">
                                <option value="">Select RAM</option>
                                <option value="2" {{(Request::get('ram_in_gb') == 2) ? "selected" : ''}}>2GB</option>
                                <option value="4" {{(Request::get('ram_in_gb') == 4) ? "selected" : ''}}>4GB</option>
                                <option value="8" {{(Request::get('ram_in_gb') == 8) ? "selected" : ''}}>8GB</option>
                                <option value="12" {{(Request::get('ram_in_gb') == 12) ? "selected" : ''}}>12GB</option>
                                <option value="16" {{(Request::get('ram_in_gb') == 16) ? "selected" : ''}}>16GB</option>
                            </select>
                        </div>
                    </div>    
                    <div class="col-6 col-md-4">
                        <div class="select-custom">
                            <label class="font-weight-bold">Storage</label>
                            <select class="select-filter form-control rounded py-1" name="rom_in_gb">
                                <option value="">Select ROM</option>
                                <option value="32" {{(Request::get('rom_in_gb') == 32) ? "selected" : ''}}>32GB</option>
                                <option value="64" {{(Request::get('rom_in_gb') == 64) ? "selected" : ''}}>64GB</option>
                                <option value="128" {{(Request::get('rom_in_gb') == 128) ? "selected" : ''}}>128GB</option>
                                <option value="256" {{(Request::get('rom_in_gb') == 256) ? "selected" : ''}}>256GB</option>
                                <option value="512" {{(Request::get('rom_in_gb') == 512) ? "selected" : ''}}>512GB</option>
                            </select>
                        </div>
                    </div>
                     
                    <div class="col-6 col-md-4">
                        <div class="">
                            <label class="font-weight-bold">Camera</label>
                            <select class="select-filter form-control rounded py-1" name="pixels">
                                <option value="">Select Camera</option>
                                <option value="12" {{(Request::get('pixels') == 12) ? "selected" : ''}}>12MP</option>
                                <option value="13" {{(Request::get('pixels') == 13) ? "selected" : ''}}>13MP</option>
                                <option value="16" {{(Request::get('pixels') == 16) ? "selected" : ''}}>16MP</option>
                                <option value="24" {{(Request::get('pixels') == 24) ? "selected" : ''}}>24MP</option>
                                <option value="48" {{(Request::get('pixels') == 48) ? "selected" : ''}}>48MP</option>
                                <option value="64" {{(Request::get('pixels') == 64) ? "selected" : ''}}>64MP</option>
                                <option value="108" {{(Request::get('pixels') == 108) ? "selected" : ''}}>108MP</option>
                            </select>
                        </div>
                    </div>  
                    <div class="col-6 col-md-4">
                        <div class="select-filter">
                            <label class="font-weight-bold">Year</label>
                            <select class="select-filter form-control rounded py-1" name="year">
                                <option value="">Select Year</option>
                                <option value="2023" {{(Request::get('year') == 2023) ? "selected" : ''}}>2023</option>
                                <option value="2022" {{(Request::get('year') == 2022) ? "selected" : ''}}>2022</option>
                                <option value="2021" {{(Request::get('year') == 2021) ? "selected" : ''}}>2021</option>
                                <option value="2020" {{(Request::get('year') == 2020) ? "selected" : ''}}>2020</option>
                                <option value="2019" {{(Request::get('year') == 2019) ? "selected" : ''}}>2019</option>
                            </select>
                        </div>
                    </div>   
                    @if(!isset($brand))
                    <div class="col-6 col-md-4">
                        <div class="select-filter">
                            <label class="font-weight-bold">Brand</label>
                            <select class="select-filter form-control rounded py-1" name="brand_id">
                                <option value="">Select Brand</option>
                                @if($brands = App\Brand::limit(20)->get())
                                @foreach($brands as $brnd)
                                <option value="{{$brnd->id}}" {{(Request::get('brand_id') == $brnd->id) ? "selected" : ''}}>{{$brnd->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div> 
                    @endif
                    <div class="col-6 col-md-4">
                        <div class="select-filter">
                            <label class="font-weight-bold">Network Band</label>
                            <select class="select-filter form-control rounded py-1" name="network_band">
                                <option value="">Select Network</option>
                                <option value="4g_band" {{(Request::get('network_band') == "4g_band") ? "selected" : ''}}>4G Band</option>
                                <option value="5g_band" {{(Request::get('network_band') == "5g_band") ? "selected" : ''}}>5G Band</option>
                            </select>
                        </div>
                    </div> 
                </div>
            </div>
    </div>
</form>