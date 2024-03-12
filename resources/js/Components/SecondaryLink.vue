<template>
    <Link
        :href="route"
        class="inline-flex items-center px-4 py-2 bg-dynacom-red border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-dynacom-red-600 active:bg-dynacom-red-900 focus:outline-none focus:border-dynacom-red focus:ring focus:ring-dynacom-red disabled:opacity-25 transition">
        <slot />
    </Link>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    props: {
        route: {
            type: [String, Object],
            required: true,
        },
    },

    components: {
        Link,
    },
};
</script>
