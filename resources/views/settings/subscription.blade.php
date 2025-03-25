@section('title')
    {{ __('Subscriptions') }}
@endsection
<x-app-layout>
    <div>
        <section class="flex flex-col gap-y-8 py-8 mt-14">
            <x-breadcrumb pageTitle="{{ __('Subscriptions') }}" breadcrumbMainUrl="{{ route('settings.subscription') }}"
                breadcrumbMain="    {{ __('Settings') }}" breadcrumbCurrent="    {{ __('Information') }}">
            </x-breadcrumb>
        </section>
    </div>
</x-app-layout>