<template>
    <div :class="'shadow overflow-hidden sm:rounded-lg ' + bgcolor">
        <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
            <div>
                <p class="text-md leading-6 text-gray-900">{{ title }}</p>
                <p class="mt-1 max-w-2xl text-3xl font-bold">
                    {{ value }}
                </p>
            </div>
            <div>
                <i :class="'fas fa-ticket-alt text-gray-900 opacity-10 text-5xl'"></i>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        name: 'Indicator',
        props: {
            title: {
                type: String,
                required: true,
                default: 'Title'
            },
            value: {
                type: Number,
                required: true,
                default: 134
            },
            bgcolor: {
                type: String,
                required: true
            }
        },
        data(){
            return {
            }
        },
    }
</script>