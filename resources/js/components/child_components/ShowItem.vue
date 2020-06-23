<!-- Shows an item with button options
  index - The index of the item - used for row coloring.
  loading - The loading attribute.
  label - The label for each item.
  label_class - The special class (often color) for the label.
  data - The data to display, an array representing the columns to display: [{title,value},...]
  data_space - Data to display with a larger amount of space - either an array that will be displayed with buttons or text.
  options - The button options available: [{action,class,target,display,disabled},...]
-->
<template>
  <div class="row p-2" v-bind:class="index%2 ? 'bg-light' : ''">
    <div v-if="label" class="col col-12 h5 font-weight-bold" :class="label_class">{{label}}</div>
    <div class="col col-7">
      <div class="row">
        <div class="col col-12 my-auto" :class="data_space ? 'col-md-3' : 'col-md-4'">
          <span v-for="item in column_1"><b>{{item.title ? item.title+': ' : ''}}</b><span :class="item.special_class">{{item.value}}</span><br/></span>
        </div>
        <div class="col col-12 my-auto" :class="data_space ? 'col-md-3' : 'col-md-4'">
          <span v-for="item in column_2"><b>{{item.title ? item.title+': ' : ''}}</b><span :class="item.special_class">{{item.value}}</span><br/></span>
        </div>
        <div class="col col-12 my-auto" :class="data_space ? 'col-md-6' : 'col-md-4'">
          <div v-if="data_space">
            <span v-if="typeof data_space == 'string'">{{data_space}}</span>
            <span v-else v-for="item in data_space"><span v-if="item.active" class="btn btn-sm btn-info m-1 disabled">{{item.name}}</span></span>
          </div>
          <span v-else v-for="item in column_3"><b>{{item.title ? item.title+': ' : ''}}</b><span :class="item.special_class">{{item.value}}</span><br/></span>
        </div>
      </div>
    </div>
    <div class="col col-5 my-auto text-right">
      <span v-for="option in options">
        <button v-if="option.action == 'modal'" class="btn btn-sm m-1" :class="option.class" data-toggle="modal" :data-target="'#'+option.target" :disabled="option.disabled">{{option.display}}</button>
        <button v-if="option.action == 'click'" class="btn btn-sm m-1" :class="option.class" v-on:click="emitEvent(option.target)" :disabled="option.disabled">{{option.display}}</button>
      </span>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['index', 'loading', 'label', 'label_class', 'data', 'data_space', 'options'],
    data() {
      return {
        column_1: [],
        column_2: [],
        column_3: []
      }
    },
    created() {
      if(this.data_space) {
        this.column_1 = this.data.slice(0,Math.ceil(this.data.length/2));
        this.column_2 = this.data.slice(Math.ceil(this.data.length/2));
      } else {
        this.column_1 = this.data.slice(0,Math.ceil(this.data.length/3));
        this.column_2 = this.data.slice(Math.ceil(this.data.length/3),Math.ceil(2*(this.data.length/3)));
        this.column_3 = this.data.slice(Math.ceil(2*(this.data.length/3)));
      }
    },
    methods: {
      emitEvent(vars) {
        this.$emit(...vars);
      }
    }
  }
</script>
