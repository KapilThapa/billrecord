@extends('layouts.app')

@section('headerstyle')
    <style>
        .input-helper{
            font-size: 11px;
            color: #ff6868;
        }
        .input-error, .input-error:focus{
            border-color: #ff6868;
        }
        .caption{
            float: left;
            display: inline-block;
        }
        .action{
            float:right;
            display: inline-block;
        }
        .panel-heading{
            min-height: 50px;
        }
        .nav-tabs{
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default" id="blockbill">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#newbills">New Bills</a></li>
                            <li><a data-toggle="tab" href="#billreceived">Bill Received</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="newbills" class="tab-pane fade in active">
                                <div class="form-horizontal" id="app1">
                                    <div class="form-group">
                                        <label for="bill_no" class="control-label col-md-3">Bill No. :</label>
                                        <div class="col-md-5" :class="{ 'control': true }">
                                            <input type="number" name="bill_no" data-vv-as="bill no." id="bill_no" v-validate="'required'" :class="{'input': true, 'is-danger input-error': errors.has('bill_no'), 'is-danger input-error': bill_error }" v-model:value="bill_no" v-on:keydown.13="focusOnEnter('#customer_name')" onfocus="this.select()" :disabled="bill_no_lock" class="form-control">
                                            <span class="input-helper" v-if="errors.has('bill_no')" class="help is-danger">@{{ errors.first('bill_no') }}</span>
                                            <span class="input-helper" v-if="bill_error">Bill no already exist.</span>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-default" v-if="bill_no_lock" @click="togglelock()"><i class="fa fa-lock"></i></button>
                                            <button class="btn btn-default" v-else @click="togglelock()"><i class="fa fa-unlock" ></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer_name" class="control-label col-md-3">C. Name :</label>
                                        <div class="col-md-8">
                                            <input type="text" id="customer_name" v-model:value="customer_name" v-on:keydown.13="focusOnEnter('#contact')" placeholder="Enter name if provided" class="form-control text-capitalize">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="contact" class="control-label col-md-3">Contact :</label>
                                        <div class="col-md-8">
                                            <input type="number" id="contact" v-model:value="contact" v-on:keydown.13="focusOnEnter('#total')" placeholder="Enter contact no. if provided" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="total" class="control-label col-md-3">Bill Total :</label>
                                        <div class="col-md-6" :class="{ 'control': true }">
                                            <input type="number" name="total" id="total" v-validate="'required'" :class="{'input': true, 'is-danger input-error': errors.has('total'), }"  v-model:value="total" v-on:keydown.13="focusOnEnter('#advance')" onfocus="this.select()" class="form-control">
                                            <span class="input-helper" v-if="errors.has('total')" class="help is-danger">@{{ errors.first('total') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="advance" class="control-label col-md-3">Advance :</label>
                                        <div class="col-md-3">
                                            <input type="number" id="advance" :class="{'input-error' : advance_error}" v-model:value="advance" v-on:keydown.13="saveBillDetail()" onfocus="this.select()" class="form-control">
                                            <span v-if="advance_error" class="input-helper">can't be greater than total</span>
                                        </div>
                                        <label for="due" class="control-label col-md-3">Due :</label>
                                        <div class="col-md-3">
                                            <input type="number" id="due" v-model:value="due" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="billreceived" class="tab-pane fade">
                                <div class="form-horizontal" id="app3">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="bill_no" class="control-label col-md-3">Bill No. :</label>
                                            <div class="col-md-5" :class="{ 'control': true }">
                                                <input type="number" name="bill_no" data-vv-as="bill no." id="bill_no" v-validate="'required'" :class="{'input': true, 'is-danger input-error': errors.has('bill_no'), 'is-danger input-error': bill_error }" v-model:value="bill_no" v-on:keydown.13="checkBillNo()" onfocus="this.select()" class="form-control">
                                                <span class="input-helper" v-if="errors.has('bill_no')" class="help is-danger">@{{ errors.first('bill_no') }}</span>
                                                <span class="input-helper" v-if="bill_error">Bill no doesn't exist.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="bill_detail.bill_no == bill_no">
                                        <div class="col-md-12">
                                            Name: @{{bill_detail.customer_name}}
                                        </div>
                                        <div class="col-md-12">
                                            Contact: @{{bill_detail.contact}})
                                        </div>
                                        <div class="col-md-12">
                                            Total : @{{bill_detail.total}}
                                        </div>
                                        <div class="col-md-12">
                                            Advance : @{{bill_detail.advance}}
                                        </div>
                                        <div class="col-md-12">
                                            Due : @{{bill_detail.balance}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-default" @click="saveBillDetail()" type="submit">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default" id="app2">
                    <div class="panel-heading">
                        <div class="caption">
                            Bill list
                        </div> 
                        <div class="action">
                            <button class="btn btn-sm btn-default" @click="fetchBills">Refresh</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" v-validate="'required'">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>Bill No</td>
                                    <td>Customer Name</td>
                                    <td>Total</td>
                                    <td>Advance</td>
                                    <td>Due</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="bill in bills">
                                    <td v-html="bill.bill_no"></td>
                                    <td v-html="bill.customer_name"></td>
                                    <td v-html="bill.total"></td>
                                    <td v-html="bill.advance"></td>
                                    <td v-html="bill.balance"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerscript')
    <script>
        var app = new Vue({
            el: '#app1',
            data:{
                bill_no:0,
                bill_no_lock: false,
                bill_error:false,
                customer_name:'',
                contact:'',
                total:0,
                advance:0,
                advance_error:false
            },
            mounted: function () {
                this.getBillNo();
            },
            methods:{
                togglelock(){
                    console.log('click');
                    if(this.bill_no_lock){
                        this.bill_no_lock= false;
                    }else{
                        this.bill_no_lock= true;
                    }
                },
                focusOnEnter(input){
                    let self=this;
                    if(input == "#customer_name"){
                        $.ajax({
                            url: '/api/checkbill/'+this.bill_no,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        })
                        .done(function(response) {
                            if(response == 'true'){
                                self.bill_error=true;
                            }else{
                                self.bill_error=false;
                                $(input).focus();
                            }
                        })
                        .fail(function(response) {
                        })
                        .always(function(response) {
                        });
                    }
                    else if(input == "#total"){
                        // check length of name
                        $(input).focus();
                    }
                    else{
                        $(input).focus();
                    }
                },
                saveBillDetail(){
                    let self=this;
                    self.$validator.validateAll().then((result) => {
                        if (result && !self.advance_error) {
                            $.ajax({
                                url: '/bill',
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    bill_no: self.bill_no,
                                    customer_name: self.customer_name,
                                    contact:self.contact,
                                    total: self.total,
                                    advance: self.advance
                                },
                                beforeSend: function( xhr ) {
                                    blockThis({
                                        target: '#blockbill'
                                    });
                                    xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                                }
                            })
                            .done(function(response) {
                                self.bill_no=0,
                                self.bill_no_lock= false,
                                self.bill_error=false,
                                self.customer_name='',
                                self.contact='',
                                self.total=0,
                                self.advance=0,
                                self.advance_error=false
                                blockThis({
                                    target: '#blockbill',
                                    textOnly: true,
                                    message: 'Saved!'
                                });

                                app1.fetchBills();
                                self.getBillNo();
                            })
                            .fail(function() {
                                blockThis({
                                    target: '#blockbill',
                                    textOnly: true,
                                    message: '<h3 style="color:red;">Errrrrrrrrrrrrror!</h3>'
                                });
                            })
                            .always(function() {
                                unblockThis('#blockbill');
                                console.log("complete");
                            });  
                        }
                    });
                },
                getBillNo(){
                    let self = this;
                    $.ajax({
                        url: '/api/getBillNo',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function( xhr ) {
                            blockThis({
                                target: '#blockbill'
                            });
                        }
                    })
                    .done(function(response) {
                        if(response.newbill == 0){
                            let last_bill = response.billno;
                            self.bill_no = last_bill.bill_no + 1;
                        }else{
                            self.bill_no=1;
                        }
                        self.bill_no_lock = true;
                        $("#customer_name").focus();
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        unblockThis('#blockbill');
                        console.log("get bill complete");
                    });
                }
            },
            computed:{
                due: function(){
                    return this.total-this.advance;
                }
            },
            watch:{
                advance: function(){
                    if(this.due<0){
                        this.advance_error=true;
                    }else{
                        this.advance_error=false;
                    }
                }
            }
        });
        /* Vue instance for different block */
        var app1 = new Vue({
            el: '#app2',
            data:{
                bills:[]
            },
            mounted: function () {
                this.fetchBills();
            },
            methods:{
                fetchBills(){
                    let self = this;
                    $.ajax({
                        url: '/bill',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function( xhr ) {
                            blockThis({
                                target: '#app2'
                            });
                        }
                    })
                    .done(function(response) {
                        self.bills = response;
                    })
                    .fail(function(error) {
                        console.log(error);
                    })
                    .always(function() {
                        unblockThis('#app2');
                        console.log("get bills list complete");
                    });
                }
            }
        });

        var app2 = new Vue({
            el: '#app3',
            data:{
                bill_no:0,
                bill_error:false,
                bill_detail:{}
            },
            mounted: function () {

            },
            methods:{
                checkBillNo(){
                    let self = this;
                    $.ajax({
                        url: '/api/getbilldetail/'+this.bill_no,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    })
                    .done(function(response) {
                        if(response.status == 'true'){
                            self.bill_error=false;
                            self.bill_detail = response.bill;
                        }else{
                            self.bill_error=true;
                        }
                    })
                    .fail(function(response) {
                    })
                    .always(function(response) {
                    });
                }
            }
        });
    </script>
@endsection