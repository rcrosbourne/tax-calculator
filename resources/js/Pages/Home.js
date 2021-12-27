import {Popover} from '@headlessui/react'
import {useForm} from '@inertiajs/inertia-react'
import React, {useEffect} from "react";
import {Inertia} from "@inertiajs/inertia";

const user = {
    name: 'Tom Cook',
    email: 'tom@example.com',
    imageUrl:
        'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
}
const navigation = [
    // {name: 'Home', href: '#', current: true},
    // {name: 'Profile', href: '#', current: false},
    // {name: 'Resources', href: '#', current: false},
    // {name: 'Company Directory', href: '#', current: false},
    // {name: 'Openings', href: '#', current: false},
]
const userNavigation = [
    {name: 'Your Profile', href: '#'},
    {name: 'Settings', href: '#'},
    {name: 'Sign out', href: '#'},
]

function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

let inputData = {
    monthlyGross: '',
    otherIncome: '',
    pensionValue: '',
    pensionType: 'PERCENTAGE',
    otherDeductions: '',
}
export default function Home({breakdown}) {
    const {data, setData, post, processing, errors, reset, transform} = useForm(inputData)

    transform((data) => {
       for(const key in data) {
           if(data[key] === '') {
               data[key] = '0.00';
           }
       }
       return data;
    });

    function submit(e) {
        e.preventDefault();
        //fix data
        post('/calculate', {
            preserveScroll: true,
            //onSuccess: () => {Inertia.visit('/')}
        });
    }

    return (
        <>
            {/*
        This example requires updating your template:

        ```
        <html class="h-full bg-gray-100">
        <body class="h-full">
        ```
      */}
            <div className="min-h-full">
                <Popover as="header" className="pb-24 bg-indigo-600">
                    {({open}) => (
                        <>
                            <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                                <div className="relative py-5 flex items-center justify-center lg:justify-between">
                                    {/* Logo */}
                                    <div className="absolute left-0 flex-shrink-0 lg:static pt-2">
                                        <a href="#">
                                            <span className="sr-only">Workflow</span>
                                            <h1 className="text-white font-island text-5xl">Tax Calculator</h1>
                                        </a>
                                    </div>

                                    {/* Right section on desktop */}
                                    <div className="hidden lg:ml-4 lg:flex lg:items-center lg:pr-0.5">

                                        {/* Profile dropdown */}
                                    </div>
                                    {/* Menu button */}
                                    {/*<div className="absolute right-0 flex-shrink-0 lg:hidden">*/}
                                    {/*    /!* Mobile menu button *!/*/}
                                    {/*    <Popover.Button*/}
                                    {/*        className="bg-transparent p-2 rounded-md inline-flex items-center justify-center text-indigo-200 hover:text-white hover:bg-white hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-white">*/}
                                    {/*        <span className="sr-only">Open main menu</span>*/}
                                    {/*        {open ? (*/}
                                    {/*            <XIcon className="block h-6 w-6" aria-hidden="true"/>*/}
                                    {/*        ) : (*/}
                                    {/*            <MenuIcon className="block h-6 w-6" aria-hidden="true"/>*/}
                                    {/*        )}*/}
                                    {/*    </Popover.Button>*/}
                                    {/*</div>*/}
                                </div>
                                <div className="hidden lg:block border-t border-white border-opacity-20 py-5">
                                    <div className="grid grid-cols-3 gap-8 items-center">
                                        <div className="col-span-2">
                                            <nav className="flex space-x-4">
                                                {navigation.map((item) => (
                                                    <a
                                                        key={item.name}
                                                        href={item.href}
                                                        className={classNames(
                                                            item.current ? 'text-white' : 'text-indigo-100',
                                                            'text-sm font-medium rounded-md bg-white bg-opacity-0 px-3 py-2 hover:bg-opacity-10'
                                                        )}
                                                        aria-current={item.current ? 'page' : undefined}
                                                    >
                                                        {item.name}
                                                    </a>
                                                ))}
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </>
                    )}
                </Popover>
                <main className="-mt-24 pb-8">
                    <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                        <h1 className="sr-only">Page title</h1>
                        {/* Main 3 column grid */}
                        <div className="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8">
                            {/* Left column */}
                            <div className="grid grid-cols-1 gap-4 lg:col-span-2">
                                <section aria-labelledby="section-1-title">
                                    <h2 className="sr-only" id="section-1-title">
                                        Section title
                                    </h2>
                                    <div className="rounded-lg bg-white overflow-hidden shadow">
                                        <div className="p-6">
                                            <form className="space-y-8 divide-y divide-gray-200" onSubmit={submit}>
                                                <div className="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                                                    <div>
                                                        <div>
                                                            <h3 className="text-lg leading-6 font-medium text-gray-900">
                                                                Earnings
                                                            </h3>
                                                            <p className="mt-1 max-w-2xl text-sm text-gray-500">
                                                                For accurate results convert annual figures to monthly
                                                            </p>
                                                        </div>

                                                        <div className="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="monthlyGross"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    Monthly Gross
                                                                </label>
                                                                <div className="mt-1 relative rounded-md shadow-sm">
                                                                    <div
                                                                        className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                        <span
                                                                            className="text-gray-500 sm:text-sm">$</span>
                                                                    </div>
                                                                    <input
                                                                        type="number"
                                                                        name="monthlyGross"
                                                                        id="monthlyGross"
                                                                        className="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                                                        placeholder="0.00"
                                                                        aria-describedby="price-currency"
                                                                        value={data.monthlyGross}
                                                                        onChange={e => setData('monthlyGross', e.target.value)}
                                                                    />
                                                                    <div
                                                                        className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
          <span className="text-gray-500 sm:text-sm" id="price-currency">
            JMD
          </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div className="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="otherIncome"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    Other Taxable Income
                                                                </label>
                                                                <div className="mt-1 relative rounded-md shadow-sm">
                                                                    <div
                                                                        className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                        <span
                                                                            className="text-gray-500 sm:text-sm">$</span>
                                                                    </div>
                                                                    <input
                                                                        type="number"
                                                                        name="otherIncome"
                                                                        id="otherIncome"
                                                                        className="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                                                        placeholder="0.00"
                                                                        aria-describedby="price-currency"
                                                                        value={data.otherIncome}
                                                                        onChange={e => setData('otherIncome', e.target.value)}

                                                                    />
                                                                    <div
                                                                        className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
          <span className="text-gray-500 sm:text-sm" id="price-currency">
            JMD
          </span>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>

                                                    <div className="pt-8 space-y-6 sm:pt-10 sm:space-y-5">
                                                        <div>
                                                            <h3 className="text-lg leading-6 font-medium text-gray-900">Deductions</h3>
                                                            <p className="mt-1 max-w-2xl text-sm text-gray-500">Enter
                                                                voluntary deductions</p>
                                                        </div>
                                                        <div className="space-y-6 sm:space-y-5">

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="pensionValue"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    Pension
                                                                </label>
                                                                <div className="mt-1 relative rounded-md shadow-sm">
                                                                    <div
                                                                        className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                    </div>
                                                                    <input
                                                                        type="number"
                                                                        name="pensionValue"
                                                                        id="pensionValue"
                                                                        className="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                                                        placeholder="10.00"
                                                                        value={data.pensionValue}
                                                                        onChange={e => setData('pensionValue', e.target.value)}
                                                                    />
                                                                    <div
                                                                        className="absolute inset-y-0 right-0 flex items-center">
                                                                        <label htmlFor="currency" className="sr-only">
                                                                            Pension Type
                                                                        </label>
                                                                        <select
                                                                            id="pensionType"
                                                                            name="pensionType"
                                                                            className="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md"
                                                                            value={data.pensionType}
                                                                            onChange={e => setData('pensionType', e.target.value)}
                                                                        >
                                                                            <option value="PERCENTAGE">percent</option>
                                                                            <option value="FIXED">dollars</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="other_deductions"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    Other deductions
                                                                </label>
                                                                <div className="mt-1 relative rounded-md shadow-sm">
                                                                    <div
                                                                        className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                        <span
                                                                            className="text-gray-500 sm:text-sm">$</span>
                                                                    </div>
                                                                    <input
                                                                        type="number"
                                                                        name="otherDeductions"
                                                                        id="otherDeductions"
                                                                        className="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                                                        placeholder="0.00"
                                                                        aria-describedby="price-currency"
                                                                        value={data.otherDeductions}
                                                                        onChange={e => setData('otherDeductions', e.target.value)}

                                                                    />
                                                                    <div
                                                                        className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
          <span className="text-gray-500 sm:text-sm" id="price-currency">
            JMD
          </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div className="pt-5">
                                                    <div className="flex justify-end">
                                                        <button
                                                            type="button"
                                                            className="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                            onClick={() => reset()}
                                                        >
                                                            Reset
                                                        </button>
                                                        <button
                                                            type="submit"
                                                            className="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        >
                                                            Calculate
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            {/* Right column */}
                            <div className="grid grid-cols-1 gap-4">
                                <section aria-labelledby="section-2-title">
                                    <h2 className="sr-only" id="section-2-title">
                                        Section title
                                    </h2>
                                    <div className="rounded-lg bg-white overflow-hidden shadow">
                                        <div className="p-6">
                                            {/* Order summary */}
                                            <section
                                                aria-labelledby="summary-heading"
                                                className="mt-16 bg-gray-50 rounded-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-5"
                                            >
                                                <h2 id="summary-heading" className="text-lg font-medium text-gray-900">
                                                    Tax Breakdown
                                                </h2>

                                                <dl className="mt-6 space-y-4">
                                                    <div className="flex items-center justify-between">
                                                        <dt className="text-sm text-gray-600">Total Earnings</dt>
                                                        <dd className="text-sm font-medium text-gray-900">${breakdown?.totalEarnings || '0.00'}</dd>
                                                    </div>
                                                    <div
                                                        className="border-t border-gray-200 pt-4 flex items-center justify-between">
                                                        <dt className="flex items-center text-sm text-gray-600">
                                                            <span>National Insurance Scheme (NIS)</span>
                                                            <a href="#"
                                                               className="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">

                                                            </a>
                                                        </dt>
                                                        <dd className="text-sm font-medium text-red-900">(${breakdown?.nationalInsuranceScheme})</dd>
                                                    </div>
                                                    <div
                                                        className="border-t border-gray-200 pt-4 flex items-center justify-between">
                                                        <dt className="flex items-center text-sm text-gray-600">
                                                            <span>Pension</span>
                                                            <a href="#"
                                                               className="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">

                                                            </a>
                                                        </dt>
                                                        <dd className="text-sm font-medium text-red-900">(${breakdown?.voluntaryDeductions?.pensionAmount})</dd>
                                                    </div>
                                                    <div
                                                        className="border-t border-gray-200 pt-4 flex items-center justify-between">
                                                        <dt className="flex text-sm text-gray-600">
                                                            <span>Education Tax</span>
                                                            <a href="#"
                                                               className="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                                            </a>
                                                        </dt>
                                                        <dd className="text-sm font-medium text-red-900">(${breakdown?.educationTax})</dd>
                                                    </div>
                                                    <div
                                                        className="border-t border-gray-200 pt-4 flex items-center justify-between">
                                                        <dt className="flex text-sm text-gray-600">
                                                            <span>National Housing Trust (NHT)</span>
                                                            <a href="#"
                                                               className="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                                            </a>
                                                        </dt>
                                                        <dd className="text-sm font-medium text-red-900">(${breakdown?.nationalHousingTrust})</dd>
                                                    </div>
                                                    <div
                                                        className="border-t border-gray-200 pt-4 flex items-center justify-between">
                                                        <dt className="flex text-sm text-gray-600">
                                                            <span>Income Tax (PAYE)</span>
                                                            <a href="#"
                                                               className="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                                            </a>
                                                        </dt>
                                                        <dd className="text-sm font-medium text-red-900">(${breakdown?.incomeTax})</dd>
                                                    </div>
                                                    <div
                                                        className="border-t border-gray-200 pt-4 flex items-center justify-between">
                                                        <dt className="flex text-sm text-gray-600">
                                                            <span>Other Deductions</span>
                                                            <a href="#"
                                                               className="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                                            </a>
                                                        </dt>
                                                        <dd className="text-sm font-medium text-red-900">(${breakdown?.voluntaryDeductions?.deductions})</dd>
                                                    </div>
                                                    <div
                                                        className="border-t border-gray-200 pt-4 flex items-center justify-between">
                                                        <dt className="text-base font-medium text-gray-900">Net Pay
                                                        </dt>
                                                        <dd className="text-sm font-medium text-red-900"></dd>
                                                    </div>
                                                </dl>

                                                <div className="mt-6">
                                                    <div
                                                        className="text-center w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-2xl md:text-3xl font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500"
                                                    >
                                                        ${breakdown?.netMonthlyIncome}
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </main>
                <footer>
                    <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 lg:max-w-7xl">
                        <div className="border-t border-gray-200 py-8 text-sm text-gray-500 text-center sm:text-left">
                            <span className="block sm:inline">&copy; 2021 Tailwind Labs Inc.</span>{' '}
                            <span className="block sm:inline">All rights reserved.</span>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    )
}
