@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('summon.print') }}" method="POST" target="_blank">
        @csrf
        <h3 class="mb-4 text-center">عدالتی حاضری فارم</h3>

        <div class="mb-3">
            <label class="form-label">جج کا نام</label>
            <input type="text" name="judge_name" class="form-control text-end" placeholder="مثلاً: سینئر سول جج، فیصل آباد">
        </div>

        <div class="mb-3">
            <label class="form-label">مقدمہ نمبر / عدالت</label>
            <input type="text" name="case_number" class="form-control text-end">
        </div>

        <div class="mb-3">
            <label class="form-label">مدعی کا نام</label>
            <input type="text" name="plaintiff_name" class="form-control text-end">
        </div>

        <div class="mb-3">
            <label class="form-label">مدعی کا پتہ</label>
            <input type="text" name="plaintiff_address" class="form-control text-end">
        </div>

        <div class="mb-3">
            <label class="form-label">نام مدعا علیہ</label>
            <input type="text" name="defendant_name" class="form-control text-end">
        </div>

        <div class="mb-3">
            <label class="form-label">ولدیت / پتہ مدعا علیہ</label>
            <input type="text" name="defendant_father_address" class="form-control text-end">
        </div>

        <div class="mb-3">
            <label class="form-label">مدعا علیہ کی حیثیت</label>
            <input type="text" name="defendant_role" class="form-control text-end" placeholder="مثلاً: گواہ، مخالف">
        </div>

        <div class="mb-3">
            <label class="form-label">تاریخ پیشی</label>
            <input type="date" name="hearing_date" class="form-control text-end">
        </div>

        <div class="mb-3">
            <label class="form-label">وقت</label>
            <input type="time" name="hearing_time" class="form-control text-end" value="08:00">
        </div>

        <div class="mb-3">
            <label class="form-label">مہینہ / سال</label>
            <input type="text" name="month_year" class="form-control text-end" placeholder="مثلاً: جولائی 2025">
        </div>

        <button type="submit" class="btn btn-primary w-100">پرنٹ کریں</button>
    </form>
</div>
@endsection
