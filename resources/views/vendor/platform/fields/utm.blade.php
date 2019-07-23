@component($typeForm,get_defined_vars())

    <div data-controller="fields--utm">
        <div class="input-group mb-3">
            <input @include('platform::partials.fields.attributes', ['attributes' => $attributes]) data-target="fields--utm.url">
            <div class="input-group-append">
                <button type="button" class="btn btn-default" data-toggle="modal"
                        data-target="#utm-{{$id}}">{{__('Generate UTM')}}</button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="utm-{{$id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header m-b">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel">{{__('UTM Generator')}}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('Campaign Source')}} - <span class="font-bold">utm_source</span></label>
                                    <input type="text" data-target="fields--utm.source" placeholder="google"
                                           class="form-control">
                                    <small class="form-text text-muted w-b-k">{{__('Original referrer: (e.g. google, newsletter)')}}</small>
                                </div>

                                <div class="form-group">
                                    <label>{{__('Campaign medium')}} - <span class="font-bold">utm_medium</span></label>
                                    <input type="text" data-target="fields--utm.medium" placeholder="cpc"
                                           class="form-control">
                                    <small class="form-text text-muted w-b-k">{{__('Marketing medium: (e.g. cpc, ppc, banner, email)')}}</small>
                                </div>

                                <div class="form-group">
                                    <label>{{__('Campaign name')}} - <span class="font-bold">utm_campaign</span></label>
                                    <input type="text" data-target="fields--utm.campaign" pattern="[a-zA-Z0-9]+"
                                           placeholder="sleeping_beds"
                                           class="form-control">
                                    <small class="form-text text-muted w-b-k">{{__('Product, promo code, or slogan (e.g. spring_sale)')}}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('Campaign term')}} - <span class="font-bold">utm_term</span></label>
                                    <input type="text" data-target="fields--utm.term" placeholder="Golf ball"
                                           class="form-control">
                                    <small class="form-text text-muted w-b-k">{{__('Paid keywords: (e.g. running+shoes)')}}</small>
                                </div>

                                <div class="form-group">
                                    <label>{{__('Campaign content')}} - <span
                                                class="font-bold">utm_content</span></label>
                                    <input type="text" data-target="fields--utm.content" placeholder="cpc"
                                           class="form-control">
                                    <small class="form-text text-muted w-b-k">{{__('Ad-specific content used to differentiate ads')}}
                                    </small>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" data-action="fields--utm#generate" data-dismiss="modal"
                                class="btn btn-default">{{__('Generate URL')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endcomponent

