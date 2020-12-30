<template>
    <div>
        <button
            class="btn-sm shadow-none border border-primary p-2"
            :class="buttonColor"
        >
            <i
                class="mr-1"
                :class="buttonIcon"
            ></i>
            {{ buttonText }}
        </button>
    </div>
</template>

<script>
export default {
    props: {
        initialIsFollowedBy: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            isFollowedBy: this.initialIsFollowedBy,
        }
    },
    computed: {
        buttonColor() {
            return this.isFollowedBy
                ? 'bg-primary text-white'
                : 'bg-white'
        },
        buttonIcon() {
            return this.isFollowedBy
                ? 'bg-primary text-white'
                : 'fas fa-user-plus'
        },
        buttonText() {
            return this.isFollowedBy
                ? 'フォロー中'
                : 'フォロー'
        },
    },
}
</script>