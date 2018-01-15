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
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default" id="app1">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="bill_no" class="control-label col-md-3">Bill No. :</label>
                                <div class="col-md-5" :class="{ 'control': true }">
                                    <input type="number" name="bill_no" data-vv-as="bill no." id="bill_no" v-validate="'required'" :class="{'input': true, 'is-danger input-error': errors.has('bill_no'), 'is-danger input-error': bill_error }" v-model:value="bill_no" v-on:keydown.13="focusOnEnter('#customer_name')" onfocus="this.select()" class="form-control">
                                    <span class="input-helper" v-if="errors.has('bill_no')" class="help is-danger">@{{ errors.first('bill_no') }}</span>
                                    <span class="input-helper" v-if="bill_error">Bill no already exist.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="customer_name" class="control-label col-md-3">C. Name :</label>
                                <div class="col-md-8">
                                    <input type="text" id="customer_name" v-model:value="customer_name" v-on:keydown.13="focusOnEnter('#contact')" placeholder="Enter name if provided" class="form-control">
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
                                <div class="col-md-6">
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

            focusOnEnter(input){
                let self=this;
                if(input == "#customer_name"){
                    $.ajax({
                        url: '/bill/checkbill/'+this.bill_no,
                        type: 'GET',
                        data: {
                            bill_no: self.bill_no,
                            customer_name: self.customer_name,
                            contact:self.contact,
                            total: self.total,
                            advance: self.advance
                        }
                    })
                    .done(function(response) {
                        if(response == 'true'){
                            self.bill_error=true;
                        }else{
                            self.bill_error=false;
                            $(input).focus();
                        }
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
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
                            data: {
                                bill_no: self.bill_no,
                                customer_name: self.customer_name,
                                contact:self.contact,
                                total: self.total,
                                advance: self.advance
                            },
                            beforeSend: function( xhr ) {
                                blockThis({
                                    target: '#app1'
                                });
                                xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                            }
                        })
                        .done(function(response) {
                            console.log(response);
                            app1.fetchBills();
                        })
                        .fail(function() {
                            console.log("error");
                        })
                        .always(function() {
                            unblockThis('#app1');
                            console.log("complete");
                        });  
                    }
                });
            },
            getBillNo(){
                let self = this;
                $.ajax({
                    url: '/bill/getBillNo',
                    type: 'GET',
                    beforeSend: function( xhr ) {
                        blockThis({
                            target: '#app1'
                        });
                    }
                })
                .done(function(response) {
                    console.log(response);
                    // self.bill_no = response.billno;
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    unblockThis('#app1');
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
                if(due<0){
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
                    type: 'GET'
                })
                .done(function(response) {
                    self.bills = response;
                })
                .fail(function(error) {
                    console.log(error);
                })
                .always(function() {
                    console.log("get bills list complete");
                });
            }
        }
    });
    </script>
@endsection