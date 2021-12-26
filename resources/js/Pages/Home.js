import {Fragment} from 'react'
import {Menu, Popover, Transition} from '@headlessui/react'
import {BellIcon, MenuIcon, QuestionMarkCircleIcon, XIcon} from '@heroicons/react/outline'
import {SearchIcon} from '@heroicons/react/solid'

const user = {
    name: 'Tom Cook',
    email: 'tom@example.com',
    imageUrl:
        'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
}
const navigation = [
    {name: 'Home', href: '#', current: true},
    {name: 'Profile', href: '#', current: false},
    {name: 'Resources', href: '#', current: false},
    {name: 'Company Directory', href: '#', current: false},
    {name: 'Openings', href: '#', current: false},
]
const userNavigation = [
    {name: 'Your Profile', href: '#'},
    {name: 'Settings', href: '#'},
    {name: 'Sign out', href: '#'},
]

function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

export default function Home() {
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
                                    <div className="absolute left-0 flex-shrink-0 lg:static">
                                        <a href="#">
                                            <span className="sr-only">Workflow</span>
                                            <img
                                                className="h-8 w-auto"
                                                src="https://tailwindui.com/img/logos/workflow-mark-indigo-300.svg"
                                                alt="Workflow"
                                            />
                                        </a>
                                    </div>

                                    {/* Right section on desktop */}
                                    <div className="hidden lg:ml-4 lg:flex lg:items-center lg:pr-0.5">
                                        <button
                                            type="button"
                                            className="flex-shrink-0 p-1 text-indigo-200 rounded-full hover:text-white hover:bg-white hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-white"
                                        >
                                            <span className="sr-only">View notifications</span>
                                            <BellIcon className="h-6 w-6" aria-hidden="true"/>
                                        </button>

                                        {/* Profile dropdown */}
                                        <Menu as="div" className="ml-4 relative flex-shrink-0">
                                            <div>
                                                <Menu.Button
                                                    className="bg-white rounded-full flex text-sm ring-2 ring-white ring-opacity-20 focus:outline-none focus:ring-opacity-100">
                                                    <span className="sr-only">Open user menu</span>
                                                    <img className="h-8 w-8 rounded-full" src={user.imageUrl} alt=""/>
                                                </Menu.Button>
                                            </div>
                                            <Transition
                                                as={Fragment}
                                                leave="transition ease-in duration-75"
                                                leaveFrom="transform opacity-100 scale-100"
                                                leaveTo="transform opacity-0 scale-95"
                                            >
                                                <Menu.Items
                                                    className="origin-top-right z-40 absolute -right-2 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                    {userNavigation.map((item) => (
                                                        <Menu.Item key={item.name}>
                                                            {({active}) => (
                                                                <a
                                                                    href={item.href}
                                                                    className={classNames(
                                                                        active ? 'bg-gray-100' : '',
                                                                        'block px-4 py-2 text-sm text-gray-700'
                                                                    )}
                                                                >
                                                                    {item.name}
                                                                </a>
                                                            )}
                                                        </Menu.Item>
                                                    ))}
                                                </Menu.Items>
                                            </Transition>
                                        </Menu>
                                    </div>

                                    {/* Search */}
                                    <div className="flex-1 min-w-0 px-12 lg:hidden">
                                        <div className="max-w-xs w-full mx-auto">
                                            <label htmlFor="desktop-search" className="sr-only">
                                                Search
                                            </label>
                                            <div className="relative text-white focus-within:text-gray-600">
                                                <div
                                                    className="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                                    <SearchIcon className="h-5 w-5" aria-hidden="true"/>
                                                </div>
                                                <input
                                                    id="desktop-search"
                                                    className="block w-full bg-white bg-opacity-20 py-2 pl-10 pr-3 border border-transparent rounded-md leading-5 text-gray-900 placeholder-white focus:outline-none focus:bg-opacity-100 focus:border-transparent focus:placeholder-gray-500 focus:ring-0 sm:text-sm"
                                                    placeholder="Search"
                                                    type="search"
                                                    name="search"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    {/* Menu button */}
                                    <div className="absolute right-0 flex-shrink-0 lg:hidden">
                                        {/* Mobile menu button */}
                                        <Popover.Button
                                            className="bg-transparent p-2 rounded-md inline-flex items-center justify-center text-indigo-200 hover:text-white hover:bg-white hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-white">
                                            <span className="sr-only">Open main menu</span>
                                            {open ? (
                                                <XIcon className="block h-6 w-6" aria-hidden="true"/>
                                            ) : (
                                                <MenuIcon className="block h-6 w-6" aria-hidden="true"/>
                                            )}
                                        </Popover.Button>
                                    </div>
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
                                        <div>
                                            <div className="max-w-md w-full mx-auto">
                                                <label htmlFor="mobile-search" className="sr-only">
                                                    Search
                                                </label>
                                                <div className="relative text-white focus-within:text-gray-600">
                                                    <div
                                                        className="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                                        <SearchIcon className="h-5 w-5" aria-hidden="true"/>
                                                    </div>
                                                    <input
                                                        id="mobile-search"
                                                        className="block w-full bg-white bg-opacity-20 py-2 pl-10 pr-3 border border-transparent rounded-md leading-5 text-gray-900 placeholder-white focus:outline-none focus:bg-opacity-100 focus:border-transparent focus:placeholder-gray-500 focus:ring-0 sm:text-sm"
                                                        placeholder="Search"
                                                        type="search"
                                                        name="search"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <Transition.Root as={Fragment}>
                                <div className="lg:hidden">
                                    <Transition.Child
                                        as={Fragment}
                                        enter="duration-150 ease-out"
                                        enterFrom="opacity-0"
                                        enterTo="opacity-100"
                                        leave="duration-150 ease-in"
                                        leaveFrom="opacity-100"
                                        leaveTo="opacity-0"
                                    >
                                        <Popover.Overlay className="z-20 fixed inset-0 bg-black bg-opacity-25"/>
                                    </Transition.Child>

                                    <Transition.Child
                                        as={Fragment}
                                        enter="duration-150 ease-out"
                                        enterFrom="opacity-0 scale-95"
                                        enterTo="opacity-100 scale-100"
                                        leave="duration-150 ease-in"
                                        leaveFrom="opacity-100 scale-100"
                                        leaveTo="opacity-0 scale-95"
                                    >
                                        <Popover.Panel
                                            focus
                                            className="z-30 absolute top-0 inset-x-0 max-w-3xl mx-auto w-full p-2 transition transform origin-top"
                                        >
                                            <div
                                                className="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y divide-gray-200">
                                                <div className="pt-3 pb-2">
                                                    <div className="flex items-center justify-between px-4">
                                                        <div>
                                                            <img
                                                                className="h-8 w-auto"
                                                                src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg"
                                                                alt="Workflow"
                                                            />
                                                        </div>
                                                        <div className="-mr-2">
                                                            <Popover.Button
                                                                className="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                                                                <span className="sr-only">Close menu</span>
                                                                <XIcon className="h-6 w-6" aria-hidden="true"/>
                                                            </Popover.Button>
                                                        </div>
                                                    </div>
                                                    <div className="mt-3 px-2 space-y-1">
                                                        <a
                                                            href="#"
                                                            className="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                                                        >
                                                            Home
                                                        </a>
                                                        <a
                                                            href="#"
                                                            className="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                                                        >
                                                            Profile
                                                        </a>
                                                        <a
                                                            href="#"
                                                            className="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                                                        >
                                                            Resources
                                                        </a>
                                                        <a
                                                            href="#"
                                                            className="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                                                        >
                                                            Company Directory
                                                        </a>
                                                        <a
                                                            href="#"
                                                            className="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                                                        >
                                                            Openings
                                                        </a>
                                                    </div>
                                                </div>
                                                <div className="pt-4 pb-2">
                                                    <div className="flex items-center px-5">
                                                        <div className="flex-shrink-0">
                                                            <img className="h-10 w-10 rounded-full" src={user.imageUrl}
                                                                 alt=""/>
                                                        </div>
                                                        <div className="ml-3 min-w-0 flex-1">
                                                            <div
                                                                className="text-base font-medium text-gray-800 truncate">{user.name}</div>
                                                            <div
                                                                className="text-sm font-medium text-gray-500 truncate">{user.email}</div>
                                                        </div>
                                                        <button
                                                            type="button"
                                                            className="ml-auto flex-shrink-0 bg-white p-1 text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        >
                                                            <span className="sr-only">View notifications</span>
                                                            <BellIcon className="h-6 w-6" aria-hidden="true"/>
                                                        </button>
                                                    </div>
                                                    <div className="mt-3 px-2 space-y-1">
                                                        {userNavigation.map((item) => (
                                                            <a
                                                                key={item.name}
                                                                href={item.href}
                                                                className="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                                                            >
                                                                {item.name}
                                                            </a>
                                                        ))}
                                                    </div>
                                                </div>
                                            </div>
                                        </Popover.Panel>
                                    </Transition.Child>
                                </div>
                            </Transition.Root>
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
                                            <form className="space-y-8 divide-y divide-gray-200">
                                                <div className="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                                                    <div>
                                                        <div>
                                                            <h3 className="text-lg leading-6 font-medium text-gray-900">Profile</h3>
                                                            <p className="mt-1 max-w-2xl text-sm text-gray-500">
                                                                This information will be displayed publicly so be
                                                                careful what you share.
                                                            </p>
                                                        </div>

                                                        <div className="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="username"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    Username
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <div className="max-w-lg flex rounded-md shadow-sm">
                  <span
                      className="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                    workcation.com/
                  </span>
                                                                        <input
                                                                            type="text"
                                                                            name="username"
                                                                            id="username"
                                                                            autoComplete="username"
                                                                            className="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                                        />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="about"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    About
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                <textarea
                    id="about"
                    name="about"
                    rows={3}
                    className="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"
                    defaultValue={''}
                />
                                                                    <p className="mt-2 text-sm text-gray-500">Write a
                                                                        few sentences about yourself.</p>
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="photo"
                                                                       className="block text-sm font-medium text-gray-700">
                                                                    Photo
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <div className="flex items-center">
                  <span className="h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                    <svg className="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                      <path
                          d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                  </span>
                                                                        <button
                                                                            type="button"
                                                                            className="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                                        >
                                                                            Change
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="cover-photo"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    Cover photo
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <div
                                                                        className="max-w-lg flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                                                        <div className="space-y-1 text-center">
                                                                            <svg
                                                                                className="mx-auto h-12 w-12 text-gray-400"
                                                                                stroke="currentColor"
                                                                                fill="none"
                                                                                viewBox="0 0 48 48"
                                                                                aria-hidden="true"
                                                                            >
                                                                                <path
                                                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                                                    strokeWidth={2}
                                                                                    strokeLinecap="round"
                                                                                    strokeLinejoin="round"
                                                                                />
                                                                            </svg>
                                                                            <div className="flex text-sm text-gray-600">
                                                                                <label
                                                                                    htmlFor="file-upload"
                                                                                    className="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500"
                                                                                >
                                                                                    <span>Upload a file</span>
                                                                                    <input id="file-upload"
                                                                                           name="file-upload"
                                                                                           type="file"
                                                                                           className="sr-only"/>
                                                                                </label>
                                                                                <p className="pl-1">or drag and drop</p>
                                                                            </div>
                                                                            <p className="text-xs text-gray-500">PNG,
                                                                                JPG, GIF up to 10MB</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div className="pt-8 space-y-6 sm:pt-10 sm:space-y-5">
                                                        <div>
                                                            <h3 className="text-lg leading-6 font-medium text-gray-900">Personal
                                                                Information</h3>
                                                            <p className="mt-1 max-w-2xl text-sm text-gray-500">Use a
                                                                permanent address where you can receive mail.</p>
                                                        </div>
                                                        <div className="space-y-6 sm:space-y-5">
                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="first-name"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    First name
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <input
                                                                        type="text"
                                                                        name="first-name"
                                                                        id="first-name"
                                                                        autoComplete="given-name"
                                                                        className="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md"
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="last-name"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    Last name
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <input
                                                                        type="text"
                                                                        name="last-name"
                                                                        id="last-name"
                                                                        autoComplete="family-name"
                                                                        className="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md"
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="email"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    Email address
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <input
                                                                        id="email"
                                                                        name="email"
                                                                        type="email"
                                                                        autoComplete="email"
                                                                        className="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="country"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    Country
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <select
                                                                        id="country"
                                                                        name="country"
                                                                        autoComplete="country-name"
                                                                        className="max-w-lg block focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm sm:max-w-xs sm:text-sm border-gray-300 rounded-md"
                                                                    >
                                                                        <option>United States</option>
                                                                        <option>Canada</option>
                                                                        <option>Mexico</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="street-address"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    Street address
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <input
                                                                        type="text"
                                                                        name="street-address"
                                                                        id="street-address"
                                                                        autoComplete="street-address"
                                                                        className="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="city"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    City
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <input
                                                                        type="text"
                                                                        name="city"
                                                                        id="city"
                                                                        autoComplete="address-level2"
                                                                        className="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md"
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="region"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    State / Province
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <input
                                                                        type="text"
                                                                        name="region"
                                                                        id="region"
                                                                        autoComplete="address-level1"
                                                                        className="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md"
                                                                    />
                                                                </div>
                                                            </div>

                                                            <div
                                                                className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                                <label htmlFor="postal-code"
                                                                       className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                                    ZIP / Postal code
                                                                </label>
                                                                <div className="mt-1 sm:mt-0 sm:col-span-2">
                                                                    <input
                                                                        type="text"
                                                                        name="postal-code"
                                                                        id="postal-code"
                                                                        autoComplete="postal-code"
                                                                        className="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        className="divide-y divide-gray-200 pt-8 space-y-6 sm:pt-10 sm:space-y-5">
                                                        <div>
                                                            <h3 className="text-lg leading-6 font-medium text-gray-900">Notifications</h3>
                                                            <p className="mt-1 max-w-2xl text-sm text-gray-500">
                                                                We'll always let you know about important changes, but
                                                                you pick what else you want to hear about.
                                                            </p>
                                                        </div>
                                                        <div
                                                            className="space-y-6 sm:space-y-5 divide-y divide-gray-200">
                                                            <div className="pt-6 sm:pt-5">
                                                                <div role="group" aria-labelledby="label-email">
                                                                    <div
                                                                        className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                                                                        <div>
                                                                            <div
                                                                                className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                                                                                id="label-email">
                                                                                By Email
                                                                            </div>
                                                                        </div>
                                                                        <div className="mt-4 sm:mt-0 sm:col-span-2">
                                                                            <div className="max-w-lg space-y-4">
                                                                                <div
                                                                                    className="relative flex items-start">
                                                                                    <div
                                                                                        className="flex items-center h-5">
                                                                                        <input
                                                                                            id="comments"
                                                                                            name="comments"
                                                                                            type="checkbox"
                                                                                            className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                                                                        />
                                                                                    </div>
                                                                                    <div className="ml-3 text-sm">
                                                                                        <label htmlFor="comments"
                                                                                               className="font-medium text-gray-700">
                                                                                            Comments
                                                                                        </label>
                                                                                        <p className="text-gray-500">Get
                                                                                            notified when someones posts
                                                                                            a comment on a posting.</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div>
                                                                                    <div
                                                                                        className="relative flex items-start">
                                                                                        <div
                                                                                            className="flex items-center h-5">
                                                                                            <input
                                                                                                id="candidates"
                                                                                                name="candidates"
                                                                                                type="checkbox"
                                                                                                className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                                                                            />
                                                                                        </div>
                                                                                        <div className="ml-3 text-sm">
                                                                                            <label htmlFor="candidates"
                                                                                                   className="font-medium text-gray-700">
                                                                                                Candidates
                                                                                            </label>
                                                                                            <p className="text-gray-500">Get
                                                                                                notified when a
                                                                                                candidate applies for a
                                                                                                job.</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div>
                                                                                    <div
                                                                                        className="relative flex items-start">
                                                                                        <div
                                                                                            className="flex items-center h-5">
                                                                                            <input
                                                                                                id="offers"
                                                                                                name="offers"
                                                                                                type="checkbox"
                                                                                                className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                                                                            />
                                                                                        </div>
                                                                                        <div className="ml-3 text-sm">
                                                                                            <label htmlFor="offers"
                                                                                                   className="font-medium text-gray-700">
                                                                                                Offers
                                                                                            </label>
                                                                                            <p className="text-gray-500">Get
                                                                                                notified when a
                                                                                                candidate accepts or
                                                                                                rejects an offer.</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div className="pt-6 sm:pt-5">
                                                                <div role="group" aria-labelledby="label-notifications">
                                                                    <div
                                                                        className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                                                                        <div>
                                                                            <div
                                                                                className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                                                                                id="label-notifications"
                                                                            >
                                                                                Push Notifications
                                                                            </div>
                                                                        </div>
                                                                        <div className="sm:col-span-2">
                                                                            <div className="max-w-lg">
                                                                                <p className="text-sm text-gray-500">These
                                                                                    are delivered via SMS to your mobile
                                                                                    phone.</p>
                                                                                <div className="mt-4 space-y-4">
                                                                                    <div className="flex items-center">
                                                                                        <input
                                                                                            id="push-everything"
                                                                                            name="push-notifications"
                                                                                            type="radio"
                                                                                            className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                                                                        />
                                                                                        <label htmlFor="push-everything"
                                                                                               className="ml-3 block text-sm font-medium text-gray-700">
                                                                                            Everything
                                                                                        </label>
                                                                                    </div>
                                                                                    <div className="flex items-center">
                                                                                        <input
                                                                                            id="push-email"
                                                                                            name="push-notifications"
                                                                                            type="radio"
                                                                                            className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                                                                        />
                                                                                        <label htmlFor="push-email"
                                                                                               className="ml-3 block text-sm font-medium text-gray-700">
                                                                                            Same as email
                                                                                        </label>
                                                                                    </div>
                                                                                    <div className="flex items-center">
                                                                                        <input
                                                                                            id="push-nothing"
                                                                                            name="push-notifications"
                                                                                            type="radio"
                                                                                            className="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                                                                        />
                                                                                        <label htmlFor="push-nothing"
                                                                                               className="ml-3 block text-sm font-medium text-gray-700">
                                                                                            No push notifications
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
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
                                                        >
                                                            Cancel
                                                        </button>
                                                        <button
                                                            type="submit"
                                                            className="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        >
                                                            Save
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
                                                    Order summary
                                                </h2>

                                                <dl className="mt-6 space-y-4">
                                                    <div className="flex items-center justify-between">
                                                        <dt className="text-sm text-gray-600">Subtotal</dt>
                                                        <dd className="text-sm font-medium text-gray-900">$99.00</dd>
                                                    </div>
                                                    <div
                                                        className="border-t border-gray-200 pt-4 flex items-center justify-between">
                                                        <dt className="flex items-center text-sm text-gray-600">
                                                            <span>Shipping estimate</span>
                                                            <a href="#"
                                                               className="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                                                <span className="sr-only">Learn more about how shipping is calculated</span>
                                                                <QuestionMarkCircleIcon className="h-5 w-5"
                                                                                        aria-hidden="true"/>
                                                            </a>
                                                        </dt>
                                                        <dd className="text-sm font-medium text-gray-900">$5.00</dd>
                                                    </div>
                                                    <div
                                                        className="border-t border-gray-200 pt-4 flex items-center justify-between">
                                                        <dt className="flex text-sm text-gray-600">
                                                            <span>Tax estimate</span>
                                                            <a href="#"
                                                               className="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                                                <span className="sr-only">Learn more about how tax is calculated</span>
                                                                <QuestionMarkCircleIcon className="h-5 w-5"
                                                                                        aria-hidden="true"/>
                                                            </a>
                                                        </dt>
                                                        <dd className="text-sm font-medium text-gray-900">$8.32</dd>
                                                    </div>
                                                    <div
                                                        className="border-t border-gray-200 pt-4 flex items-center justify-between">
                                                        <dt className="text-base font-medium text-gray-900">Order
                                                            total
                                                        </dt>
                                                        <dd className="text-base font-medium text-gray-900">$112.32</dd>
                                                    </div>
                                                </dl>

                                                <div className="mt-6">
                                                    <button
                                                        type="submit"
                                                        className="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500"
                                                    >
                                                        Checkout
                                                    </button>
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
