<?php  defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div 
  x-data="{loading: true, onLoading() {setTimeout(() => {this.loading = false}, 1500)}}"
  x-init="onLoading()">
  <div
    class="fixed bg-white left-0 top-0 w-screen h-screen z-[9999] flex justify-center items-center"
    x-show="loading">
    <div 
      class="spinner-grow inline-block w-8 h-8 bg-primary-100 rounded-full opacity-0 mb-5"
      role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</div>