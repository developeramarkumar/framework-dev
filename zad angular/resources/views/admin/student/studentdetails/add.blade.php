@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <h1> Student Add <small>Details</small> </h1>
      <ol class="breadcrumb">
       <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>        
      </ol>
</section>
<section class="content">
  	<div class="box">             
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-lg-12 ">                  
                    {{ Form::open(['route'=>['admin.student.post']]) }}
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="col-lg-6">
                                            <div class="radio-custom radio-danger">
                                                <input type="radio" value="1"   name="member_type"   id="danger">
                                                <label for="danger">HUDA Complex</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="radio-custom radio-success">
                                                <input type="radio" value="2"   name="member_type"   id="success">
                                                <label for="success">Jind Road</label>
                                            </div>
                                        </div>
                                         
                                        <p class="text-danger">{{ $errors->first('member_type') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>                   
                        <hr>
                        <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="col-md-12 ">
                                         <div class="col-lg-8">                         
                                            <div class="form-group">
                                                {{ Form::label('whole_year','
                                                    HUDA Complex    Jind Road
                                                    Kindly Enter Number of Months:',['class'=>'col-lg-8 control-label']) }}
                                                <div class="col-lg-4">
                                                    {{ Form::text('whole_year','',['class'=>'form-control col-lg-4   required']) }}
                                                    <p class="text-danger">{{ $errors->first('whole_year') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="radio-custom radio-success">
                                                <input type="checkbox" value="2"      id="success">
                                                <label for="success">Whole Year Payment</label>

                                            </div>
                                        </div>
                                            
                                    </div>
                                </div>
                            </div>
                        </div>{{--row end --}} <hr>
                         <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="col-md-12">
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('session','Session',['class'=>' control-label']) }}                         
                                                {!! Form::select('Session',
                                                [
                                                   '2017-2018' => '2017-2018',
                                                   '2018-2019' => '2018-2019',
                                                   '2019-2020' => '2019-2020',
                                                    
                                                ]
                                                , null, ['class'=>'form-control','placeholder'=>'choose Session','required']) !!}
                                                <p class="text-danger">{{ $errors->first('session') }}</p>
                                            </div>
                                        </div>
                                         <div class="col-lg-2">                         
                                            <div class="form-group">
                                                {{ Form::label('class','Class',['class'=>' control-label']) }}
                                                {!! Form::select('Class',
                                                [
                                                   'Pre-Nur' => 'Pre-Nur',
                                                   'Pre-Nur' => 'Pre-Nur',
                                                   'Pre-Nur' => 'Pre-Nur',
                                                ]
                                                , null, ['class'=>'form-control','placeholder'=>'Select Class','required']) !!}
                                                <p class="text-danger">{{ $errors->first('session') }}</p>
                                            </div>
                                        </div>
                                            <div class="col-lg-2">                         
                                            <div class="form-group">
                                                {{ Form::label('section','Section',['class'=>' control-label']) }}
                                                {!! Form::select('Section',
                                                [
                                                   'Dimond' => 'Dimond',
                                                  
                                                    
                                                ]
                                                , null, ['class'=>'form-control','placeholder'=>'Select Section','required']) !!}
                                                <p class="text-danger">{{ $errors->first('session') }}</p>
                                            </div>
                                        </div>
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('date_of_admission','Date of Admission',['class'=>' control-label']) }}                         
                                                {{ Form::text('date_of_admission','',array('class' => 'form-control' )) }}
                                                <p class="text-danger">{{ $errors->first('date_of_admission') }}</p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                         </div> {{--row end --}}
                         <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="col-md-12">
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('student_name','Student Name',['class'=>' control-label']) }}                         
                                                {{ Form::text('student_name','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('student_name') }}</p>
                                            </div>
                                        </div>
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('father_name','Father Name',['class'=>' control-label']) }}                         
                                                {{ Form::text('father_name','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('father_name') }}</p>
                                            </div>
                                        </div>
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('mother_name','Mother Name',['class'=>' control-label']) }}                         
                                                {{ Form::text('mother_name','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('mother_name') }}</p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>{{--row end --}}   
                        <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="col-md-12">
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('date_of_birth','Date of Birth',['class'=>' control-label']) }}                         
                                                {{ Form::text('date_of_birth','',['class'=>'form-control   required']) }}
                                                <p class="text-danger">{{ $errors->first('date_of_birth') }}</p>
                                            </div>
                                        </div> 
                                        <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('religion','Religion',['class'=>' control-label']) }}
                                                {!! Form::select('Religion',
                                                [
                                                   'Hindu' => 'Hindu',
                                                   'Muslim' => 'Muslim',
                                                   'Sikh' => 'Sikh', 
                                                   'Christian' => 'Christian',
                                                   'Other' => 'other',
                                                ]
                                                , null, ['class'=>'form-control','placeholder'=>'Select Religion','required']) !!}
                                                <p class="text-danger">{{ $errors->first('Religion') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('category','Category',['class'=>' control-label']) }}
                                                {!! Form::select('Category',
                                                [
                                                   'General' => 'General',
                                                   'OBC' => 'OBC',
                                                   'SC' => 'SC',
                                                   'ST' => 'ST',
                                                ]
                                                , null, ['class'=>'form-control','placeholder'=>'Select Religion','required']) !!}
                                                <p class="text-danger">{{ $errors->first('Religion') }}</p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                         </div> {{--row end --}} 
                         <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="col-md-12">
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('address','Address',['class'=>'control-label']) }}
                                                 {{ Form::textarea('address','',['class'=>'form-control','rows'=>2  ,'style'=>'resize:none']) }}
                                                 
                                            </div>
                                        </div>
                                         <div class="col-lg-2">                         
                                            <div class="form-group">
                                                {{ Form::label('state','State',['class'=>' control-label']) }}
                                                {!! Form::select('state',
                                                [
                                                   'Pre-Nur' => 'Pre-Nur',
                                                   'Pre-Nur' => 'Pre-Nur',
                                                   'Pre-Nur' => 'Pre-Nur',
                                                ]
                                                , null, ['class'=>'form-control','placeholder'=>'Select State','required']) !!}
                                                <p class="text-danger">{{ $errors->first('State') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">                         
                                            <div class="form-group">
                                                {{ Form::label('city','City',['class'=>' control-label']) }}
                                                {!! Form::select('City',
                                                [
                                                   'Dimond' => 'Dimond',
                                                  
                                                ]
                                                , null, ['class'=>'form-control','placeholder'=>'Select Section','required']) !!}
                                                <p class="text-danger">{{ $errors->first('city') }}</p>
                                            </div>
                                        </div>
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('pincode','Pincode',['class'=>' control-label']) }}                         
                                                {{ Form::text('Pincode','',array('class' => 'form-control' )) }}
                                                <p class="text-danger">{{ $errors->first('Pincode') }}</p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div> {{--row end --}}               
                        <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="col-md-12">
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('mobile_one','Contact Mobile Number',['class'=>' control-label']) }}                         
                                                {{ Form::text('mobile_one','',['class'=>'form-control required']) }}
                                                 
                                            </div>
                                        </div>
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('mobile_two','Contact Mobile Number',['class'=>' control-label']) }}                         
                                                {{ Form::text('mobile_two','',['class'=>'form-control required']) }}
                                            </div>
                                        </div>
                                        <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('mobile_sms','Contact SMS Mobile Number ',['class'=>' control-label']) }}                         
                                                {{ Form::text('mobile_sms','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('mobile_sms') }}</p>

                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>{{--row end --}}   
                        <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="col-md-12">
                                         <div class="col-lg-12">                         
                                            <div class="form-group">
                                                {{ Form::label('st_photo','Student Photo (Optional)',['class'=>' control-label']) }}                         
                                                {{ Form::file('st_photo','',['class'=>'form-control ']) }}
                                                 
                                            </div>
                                        </div>
                                          
                                           
                                    </div>
                                </div>
                            </div>
                        </div>{{--row end --}}  <hr>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-1e text-center">
                                        <h4 align="center">Installment Pattren :</h4><hr>
                                        <div class="col-lg-6">
                                            <div class="radio-custom radio-danger">
                                                <input type="radio" value="1"   name="member_type"   id="danger">
                                                <label for="danger">Quarterly</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="radio-custom radio-success">
                                                <input type="radio" value="2"   name="member_type"   id="success">
                                                <label for="success">Monthly</label>
                                            </div>
                                        </div>
                                         
                                        <p class="text-danger">{{ $errors->first('member_type') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>      {{--row end --}} <hr> 
                        <div class="row">
                            <div class="col-md-12  ">
                                   <h4> Discount Allowed</h4><hr>

                                    <div class="col-lg-2">
                                        <div class="radio-custom radio-danger">
                                            <input type="radio" value="1" name="member_type" id="danger">
                                            <label for="danger">Sibling</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="radio-custom radio-success">
                                            <input type="radio" value="2"   name="member_type"   id="success">
                                            <label for="success">   Staff</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="radio-custom radio-success">
                                            <input type="radio" value="2"   name="member_type"   id="success">
                                            <label for="success">None</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">                                        
                                      <div class="form-group">
                                            {{ Form::label('total_discount','
                                                Total Discount:',['class'=>'col-lg-4 control-label']) }}
                                            <div class="col-lg-4">
                                                {{ Form::text('total_discount','',['class'=>'form-control col-lg-8   required']) }}
                                                <p class="text-danger">{{ $errors->first('total_discount') }}</p>
                                            </div>
                                        </div>
                                     </div>                                         
                                    <p class="text-danger">{{ $errors->first('member_type') }}</p>
                            </div>                                 
                        </div>      {{--row end --}} <hr> 
                        <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="col-md-12">
                                         <div class="col-lg-6 ">                         
                                            <div class="form-group">
                                                {{ Form::label('total_fees','Total Fees',['class'=>' control-label']) }}                         
                                                {{ Form::text('total_fees','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('total_fees') }}</p>
                                            </div>
                                        </div>
                                         <div class="col-lg-6">                         
                                            <div class="form-group">
                                                {{ Form::label('quarter_fees','Quarter Fees',['class'=>' control-label']) }}                         
                                                {{ Form::text('quarter_fees','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('quarter_fees') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>{{--row end --}}   
                         <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="col-md-12">
                                         <div class="col-lg-6 ">                         
                                            <div class="form-group">
                                                {{ Form::label('receipt_no','Receipt No *',['class'=>' control-label']) }}                         
                                                {{ Form::text('receipt_no','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('receipt_no') }}</p>
                                            </div>
                                        </div>
                                         <div class="col-lg-6">                         
                                            <div class="form-group">
                                                {{ Form::label('receipt_date','Receipt Date',['class'=>' control-label']) }}                         
                                                {{ Form::text('receipt_date','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('receipt_date') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>{{--row end --}} 
                        <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <div class="col-md-12">
                                         <div class="col-lg-6 ">                         
                                            <div class="form-group">
                                                {{ Form::label('received_fees','Received Fees ',['class'=>' control-label']) }}                         
                                                {{ Form::text('received_fees','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('received_fees') }}</p>
                                            </div>
                                        </div>
                                         <div class="col-lg-6">                         
                                            <div class="form-group">
                                                {{ Form::label('balance_fees','Balance Fees',['class'=>' control-label']) }}                         
                                                {{ Form::text('balance_fees','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('balance_fees') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>{{--row end --}}
                         <hr>
                        <div class="row">{{--row start --}}
                            <div class="col-md-8 col-md-offset-2">                        
                                <div class="form-group">
                                <div class="col-lg-4 col-lg-offset-1 ">
                                    {{ Form::label('Quarter Balance :','
                                        Quarter Balance :',['class'=>'col-lg-12 control-label']) }}
                                        </div>
                                        <div class="col-lg-6  ">
                                          {{ Form::text('quarter_balance :','',['class'=>'form-control col-lg-6   required']) }}
                                         <p class="text-danger">{{ $errors->first('quarter_balance') }}</p>
                                        </div>
                                </div>
                            </div>
                        </div>{{--row end --}}
                        <hr>
                        <div class="row">{{--row start --}}
                            <div class="col-md-12 ">
                            <h5 style="margin-left: 30px;">If Payment Mode is Cheque</h5>
                                <div class="form-group">
                                    <div class="col-md-12">
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('cheque_no','Cheque No',['class'=>' control-label']) }}                         
                                                {{ Form::text('cheque_no','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('cheque_no') }}</p>
                                            </div>
                                        </div>
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('bank_name','Bank Name',['class'=>' control-label']) }}                         
                                                {{ Form::text('bank_name','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('bank_name') }}</p>
                                            </div>
                                        </div>
                                         <div class="col-lg-4">                         
                                            <div class="form-group">
                                                {{ Form::label('cheque_date','Cheque Date',['class'=>' control-label']) }}                         
                                                {{ Form::text('cheque_date','',['class'=>'form-control required']) }}
                                                <p class="text-danger">{{ $errors->first('cheque_date') }}</p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>{{--row end --}} 
                        <hr> 
                         
                         <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-success">Submit</button>
                    </div>
                </div>                        
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
@endsection
 @push('scripts')
 <script type="text/javascript">
function confirm_delete() {
  return confirm('are you sure?');
}
</script>

@endpush