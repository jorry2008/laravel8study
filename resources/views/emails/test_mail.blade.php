@component('mail::message')
# Introduction

莫慌，这是一个测试邮件，The body of your message.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
