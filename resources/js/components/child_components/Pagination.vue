<!-- Shows the pagination links on a given page
  page - The number of the page currently displayed.
  pages - The array of page numbers currently visible.
  - emit - change - sends back the new page number.
-->
<template>
  <div v-if="pages.length > 1" class="row">
    <div class="col col-12 flex-center text-center">
      <span v-for="selected_page in pages">
        <span v-if="selected_page !== ''">
          <button type="button" v-on:click="$emit('change',selected_page)" class="btn btn-sm p-1 m-1" v-bind:class="selected_page == page ? 'btn-dark' : 'btn-light'">{{selected_page}}</button>
        </span>
        <span v-else>&nbsp;&nbsp;&nbsp;</span>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  props: ['page', 'pages']
}
</script>
