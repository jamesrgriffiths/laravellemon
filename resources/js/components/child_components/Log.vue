<!-- Shows a log with options for viewing more info or deleting them in a row format
  index - The index of the log - used for row coloring.
  log - The log data object.
  loading - The loading attribute.
  trace_modal_id - The id for referencing the trace view modal.
  - emit - delete - uses the id of the log and the type of deletion.
-->
<template>
  <div class="row p-2" v-bind:class="index%2 ? '' : 'bg-light'">
    <div class="col col-12">
      <b v-bind:class="log.type == 'Error' ? 'text-danger' : 'text-info'">{{log.type}}</b><br/>
    </div>
    <div class="col col-12 col-md-3">
      <b>Time:&nbsp;</b>{{convertDateTime(log.created_at)}}<br/>
      <b>User:&nbsp;</b>{{log.user ? log.user.name + " (" + log.user_type_name + ")" : 'None'}}<br/>
      <b>IP:&nbsp;</b>{{log.ip_address}}
    </div>
    <div class="col col-12 col-md-3">
      <b>Device:&nbsp;</b><span v-bind:title="log.device">{{log.device_cleaned}}</span><br/>
      <b>URL:&nbsp;</b>{{log.url}}
      <span v-if="log.type == 'Request'"><br/><b>Run Time:&nbsp;</b>{{log.run_time}}</span>
    </div>
    <div class="col col-12 col-md-3">
      <b>Class:&nbsp;</b>{{log.class}}<br/>
      <b>Message:&nbsp;</b>{{log.message}}
    </div>
    <div class="col col-12 col-md-3 my-auto text-right">
      <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" :data-target="'#'+trace_modal_id">Full Trace</button>
      <button type="button" class="btn btn-sm btn-outline-danger m-1" v-on:click="$emit('delete',log.id,index)" :disabled="loading">Delete</button>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['index', 'log', 'loading', 'trace_modal_id']
  }
</script>
