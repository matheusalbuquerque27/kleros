<form method="POST" action="{{ route('locale.update') }}" class="{{ $formClass ?? 'hidden sm:block' }}">
    @csrf
    <label for="{{ $selectId ?? 'locale' }}" class="sr-only">{{ __('site.locale.label') }}</label>
    <select
        id="{{ $selectId ?? 'locale' }}"
        name="locale"
        onchange="this.form.submit()"
        class="bg-[#1a1821]/70 border border-white/20 rounded-md px-3 py-2 text-sm focus:border-white/60 focus:outline-none">
        @foreach(($availableLocales ?? []) as $localeCode => $localeLabel)
            <option value="{{ $localeCode }}" @selected($localeCode === ($currentLocale ?? app()->getLocale()))>
                {{ $localeLabel }}
            </option>
        @endforeach
    </select>
</form>
