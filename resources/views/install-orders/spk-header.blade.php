<div class="h-24">
    <div class="flex w-full items-center px-10 pt-8 border-b pb-2">
        <img class="ml-2 w-[125px]" src="{{ asset('storage/' . $company->logo) }}" alt="">
        <div class="ml-4 w-[600px]">
            <span class="flex mt-1 text-sm font-semibold">{{ $company->name }}</span>
            <span class="flex mt-1 text-xs">{{ $company->address }} - {{ $company->city }} | {{ $company->province }} -
                Indonesia</span>
            <span class="flex mt-1 text-xs">Ph. {{ $company->phone }} | Mobile. {{ $company->m_phone }} </span>
            <span class="flex mt-1 text-xs">{{ $company->email }} | {{ $company->website }}</span>
        </div>
    </div>
</div>
