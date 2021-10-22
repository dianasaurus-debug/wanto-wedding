<template>
    <div :class="classes">
        <slot/>
    </div>
</template>
<script>
import { defineComponent } from 'vue'
import { Link } from '@inertiajs/inertia-vue3';

export default defineComponent({
    components: {
        Link,
    },
    props: ['active'],

    computed: {
        classes() {
            return this.active
                ? 'w-full flex items-center text-blue-400 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer bg-gray-200'
                : 'w-full flex items-center text-blue-400 h-10 pl-4 hover:bg-gray-200 rounded-lg cursor-pointer'
        }
    }
})
</script>
