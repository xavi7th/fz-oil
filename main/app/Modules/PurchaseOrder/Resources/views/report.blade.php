@extends('purchaseorder::layouts.master')

@section('customCss')

<style>
    /*! CSS Used from: http://fz-project.test/_tt/assets/styling.css?20190826&lightMode=1 */
.sf-minitoolbar{background-color:#222;border-top-left-radius:4px;bottom:0;box-sizing:border-box;display:none;height:36px;padding:6px;position:fixed;right:0;z-index:99999;}
.sf-minitoolbar a{display:block;}
.sf-minitoolbar svg{max-height:24px;max-width:24px;display:inline;}
.sf-toolbar-clearer{clear:both;height:36px;}
.sf-display-none{display:none;}
.sf-toolbarreset *{box-sizing:content-box;vertical-align:baseline;letter-spacing:normal;width:auto;}
.sf-toolbarreset{background-color:#222;bottom:0;box-shadow:0 -1px 0 rgba(0, 0, 0, 0.2);color:#EEE;font:11px Arial, sans-serif;left:0;margin:0;padding:0 36px 0 0;position:fixed;right:0;text-align:left;text-transform:none;z-index:99999;direction:ltr;-webkit-font-smoothing:subpixel-antialiased;-moz-osx-font-smoothing:auto;}
.sf-toolbarreset svg{height:20px;width:20px;display:inline-block;}
.sf-toolbarreset .hide-button{background:#444;display:block;position:absolute;top:0;right:0;width:36px;height:36px;cursor:pointer;text-align:center;}
.sf-toolbarreset .hide-button svg{max-height:18px;margin-top:10px;}
.sf-toolbar-block{cursor:default;display:block;float:left;height:36px;margin-right:0;white-space:nowrap;max-width:15%;}
.sf-toolbar-block > a,.sf-toolbar-block > a:hover{display:block;text-decoration:none;color:inherit;}
.sf-toolbar-block span{display:inline-block;}
.sf-toolbar-block .sf-toolbar-value{color:#F5F5F5;font-size:13px;line-height:36px;padding:0;}
.sf-toolbar-block .sf-toolbar-label{color:#AAA;font-size:12px;}
.sf-toolbar-block .sf-toolbar-info{border-collapse:collapse;display:table;z-index:100000;}
.sf-toolbar-block .sf-toolbar-info-piece{border-bottom:solid transparent 3px;display:table-row;}
.sf-toolbar-block .sf-toolbar-info-piece-additional,.sf-toolbar-block .sf-toolbar-info-piece-additional-detail{display:none;}
.sf-toolbar-block .sf-toolbar-info-group{margin-bottom:4px;padding-bottom:2px;border-bottom:1px solid #333333;}
.sf-toolbar-block .sf-toolbar-info-group:last-child{margin-bottom:0;padding-bottom:0;border-bottom:none;}
.sf-toolbar-block .sf-toolbar-info-piece .sf-toolbar-status{padding:2px 5px;margin-bottom:0;}
.sf-toolbar-block .sf-toolbar-info-piece:last-child{margin-bottom:0;}
div.sf-toolbar .sf-toolbar-block .sf-toolbar-info-piece a{color:#99CDD8;text-decoration:underline;}
div.sf-toolbar .sf-toolbar-block a:hover{text-decoration:none;}
.sf-toolbar-block .sf-toolbar-info-piece b{color:#AAA;display:table-cell;font-size:11px;padding:4px 8px 4px 0;}
.sf-toolbar-block:not(.sf-toolbar-block-dump) .sf-toolbar-info-piece span{color:#F5F5F5;}
.sf-toolbar-block .sf-toolbar-info-piece span{font-size:12px;}
.sf-toolbar-block .sf-toolbar-info{background-color:#444;bottom:36px;color:#F5F5F5;display:none;padding:9px 0;position:absolute;}
.sf-toolbar-block .sf-toolbar-info:empty{visibility:hidden;}
.sf-toolbar-block .sf-toolbar-status{display:inline-block;color:#FFF;background-color:#666;padding:3px 6px;margin-bottom:2px;vertical-align:middle;min-width:15px;min-height:13px;text-align:center;}
.sf-toolbar-block .sf-toolbar-status-green{background-color:#4F805D;}
.sf-toolbar-block.sf-toolbar-status-yellow{background-color:#A46A1F;color:#FFF;}
.sf-toolbar-block-request .sf-toolbar-status{color:#FFF;display:inline-block;font-size:14px;height:36px;line-height:36px;padding:0 10px;}
.sf-toolbar-block-ajax .sf-toolbar-icon{cursor:pointer;}
.sf-toolbar-status-yellow .sf-toolbar-label{color:#FFF;}
.sf-toolbar-status-yellow svg path{fill:#FFF;}
.sf-toolbar-block .sf-toolbar-icon{display:block;height:36px;padding:0 7px;overflow:hidden;text-overflow:ellipsis;}
.sf-toolbar-block-request .sf-toolbar-icon{padding-left:0;padding-right:0;}
.sf-toolbar-block .sf-toolbar-icon svg{border-width:0;position:relative;top:8px;vertical-align:baseline;}
.sf-toolbar-block .sf-toolbar-icon svg + span{margin-left:4px;}
.sf-toolbar-block:hover{position:relative;}
.sf-toolbar-block:hover .sf-toolbar-icon{background-color:#444;position:relative;z-index:10002;}
.sf-toolbar-block:hover .sf-toolbar-info{display:block;padding:10px;max-width:480px;max-height:480px;word-wrap:break-word;overflow:hidden;overflow-y:auto;}
.sf-toolbar-info-piece b.sf-toolbar-ajax-info{color:#F5F5F5;}
.sf-toolbar-ajax-requests{table-layout:auto;width:100%;}
.sf-toolbar-ajax-requests td{background-color:#444;border-bottom:1px solid #777;color:#F5F5F5;font-size:12px;padding:4px;}
.sf-toolbar-ajax-requests tr:last-child td{border-bottom:0;}
.sf-toolbar-ajax-requests th{background-color:#222;border-bottom:0;color:#AAA;font-size:11px;padding:4px;}
.sf-ajax-request-url{max-width:250px;line-height:9px;overflow:hidden;text-overflow:ellipsis;}
.sf-toolbar-ajax-requests .sf-ajax-request-url a{text-decoration:none;}
.sf-toolbar-ajax-requests .sf-ajax-request-url a:hover{text-decoration:underline;}
.sf-ajax-request-duration{text-align:right;}
.sf-toolbar-block.sf-toolbar-block-dump .sf-toolbar-info{max-width:none;width:100%;position:fixed;box-sizing:border-box;left:0;}
.sf-toolbar-block-dump pre.sf-dump{background-color:#222;border-color:#777;border-radius:0;margin:6px 0 12px 0;}
.sf-toolbar-block-dump pre.sf-dump .sf-dump-search-wrapper{margin-bottom:5px;}
.sf-toolbar-block-dump pre.sf-dump span.sf-dump-search-count{color:#333;font-size:12px;}
.sf-toolbar-block-dump .sf-toolbar-info-piece{display:block;}
.sf-toolbar-icon .sf-toolbar-label,.sf-toolbar-icon .sf-toolbar-value{display:none;}
.sf-toolbar-block .sf-toolbar-info-piece-additional-detail{color:#AAA;font-size:12px;}
.sf-toolbar-status-yellow .sf-toolbar-info-piece-additional-detail{color:#FFF;}
@media (min-width: 768px){
.sf-toolbar-icon .sf-toolbar-label,.sf-toolbar-icon .sf-toolbar-value{display:inline;}
.sf-toolbar-block .sf-toolbar-icon svg{top:6px;}
.sf-toolbar-block-time .sf-toolbar-icon svg{display:none;}
.sf-toolbar-block-time .sf-toolbar-icon svg + span{margin-left:0;}
.sf-toolbar-block .sf-toolbar-icon{padding:0 10px;}
.sf-toolbar-block-time .sf-toolbar-icon{padding-right:5px;}
.sf-toolbar-block-request .sf-toolbar-icon{padding-left:0;padding-right:0;}
.sf-toolbar-block-request .sf-toolbar-status + svg{margin-left:5px;}
.sf-toolbar-block-request:hover .sf-toolbar-info{max-width:none;}
.sf-toolbar-block .sf-toolbar-info-piece b{font-size:12px;}
.sf-toolbar-block .sf-toolbar-info-piece span{font-size:13px;}
.sf-toolbar-block-right{float:right;margin-left:0;margin-right:0;}
}
@media (min-width: 1024px){
.sf-toolbar-block .sf-toolbar-info-piece-additional,.sf-toolbar-block .sf-toolbar-info-piece-additional-detail{display:inline;}
.sf-toolbar-block .sf-toolbar-info-piece-additional:empty,.sf-toolbar-block .sf-toolbar-info-piece-additional-detail:empty{display:none;}
}
@media print{
.sf-toolbar{display:none;}
}
.sf-toolbar-block.sf-toolbar-block-fullwidth .sf-toolbar-info{max-width:none;width:100%;position:fixed;box-sizing:border-box;left:0;}
.sf-toolbar-previews{table-layout:auto;width:100%;}
.sf-toolbar-previews td{background-color:#444;border-bottom:1px solid #777;color:#F5F5F5;font-size:12px;padding:4px;}
.sf-toolbar-previews td.monospace{font-family:SFMono-Regular,Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace;font-size:10px;}
.sf-toolbar-previews td.sf-query{font-family:SFMono-Regular,Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace;font-size:11px;white-space:normal;vertical-align:middle;}
.sf-toolbar-previews tr:last-child td{border-bottom:0;}
.sf-toolbar-previews th{background-color:#222;border-bottom:0;color:#AAA;font-size:11px;padding:4px;}
.sf-minitoolbar .open-button svg{max-height:18px;}
.sf-minitoolbar{background-color:#3439BC;}
.sf-toolbarreset{background-color:#EEF1F3;box-shadow:0px 1px 5px rgba(0, 0, 0, 0.2);color:#212529;}
.sf-toolbarreset .hide-button{background:#3439BC;}
.sf-toolbar-block .sf-toolbar-value{color:#212529;}
.sf-toolbar-block .sf-toolbar-label{color:#212529;}
.sf-toolbar-block .sf-toolbar-info-group{border-bottom:1px solid #212529;}
div.sf-toolbar .sf-toolbar-block .sf-toolbar-info-piece a{color:#3439bc;}
.sf-toolbar-block .sf-toolbar-info-piece b{color:#212529;}
.sf-toolbar-block:not(.sf-toolbar-block-dump) .sf-toolbar-info-piece span{color:#212529;}
.sf-toolbar-block:not(.sf-toolbar-block-dump) .sf-toolbar-info-piece span.sf-toolbar-status{color:#FFF;}
.sf-toolbar-block .sf-toolbar-info{background-color:#fff;color:#212529;}
.sf-toolbar-block .sf-toolbar-status{background-color:#212529;border-radius:9999px;}
.sf-toolbar-icon .sf-toolbar-status{border-radius:0px;}
.sf-toolbar-block .sf-toolbar-status-green{background-color:#38A169;}
.sf-toolbar-block.sf-toolbar-status-yellow{background-color:#D69E2E;}
.sf-toolbar-block:hover .sf-toolbar-icon{background-color:#fff;border-bottom:2px solid #4040c8;box-sizing:border-box;}
.sf-toolbar-block:hover .sf-toolbar-info{box-shadow:0px -2px 6px rgba(0,0,0,0.1);border-top-left-radius:3px;border-top-right-radius:3px;}
.sf-toolbar-block.sf-toolbar-status-yellow .sf-toolbar-icon{background-color:#D69E2E;}
.sf-toolbar-info-piece b.sf-toolbar-ajax-info{color:#212529;}
.sf-toolbar-ajax-requests td,.sf-toolbar-previews td{background-color:#fff;border-bottom:1px solid #212529;color:#212529;}
.sf-toolbar-ajax-requests th,.sf-toolbar-previews th{background-color:#EEF1F3;color:#212529;}
.sf-toolbar-block .sf-toolbar-info-piece-additional-detail{color:#EEF1F3;}
/*! CSS Used from: Embedded */
pre.sf-dump .sf-dump-compact{display:none;}
/*! CSS Used from: http://fz-project.test/css/dashboard-app.css */
table.dataTable{border-collapse:separate!important;clear:both;margin-bottom:6px!important;margin-top:6px!important;max-width:none!important;}
table.dataTable td,table.dataTable th{box-sizing:content-box;}
div.dataTables_wrapper div.dataTables_length label{font-weight:400;text-align:left;white-space:nowrap;}
div.dataTables_wrapper div.dataTables_length select{display:inline-block;width:75px;}
div.dataTables_wrapper div.dataTables_filter{text-align:right;}
div.dataTables_wrapper div.dataTables_filter label{font-weight:400;text-align:left;white-space:nowrap;}
div.dataTables_wrapper div.dataTables_filter input{display:inline-block;margin-left:.5em;width:auto;}
table.dataTable thead>tr>th:active{outline:none;}
@media screen and (max-width:767px){
div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length{text-align:center;}
}
div.table-responsive>div.dataTables_wrapper>div.row{margin:0;}
div.table-responsive>div.dataTables_wrapper>div.row>div[class^=col-]:first-child{padding-left:0;}
div.table-responsive>div.dataTables_wrapper>div.row>div[class^=col-]:last-child{padding-right:0;}
*,:after,:before{box-sizing:border-box;}
body{background-color:#fff;color:#3e4b5b;font-family:Avenir Next W01,Proxima Nova W01,Rubik,-apple-system,system-ui,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif;font-size:14.4px;font-size:.9rem;font-weight:400;line-height:1.5;margin:0;text-align:left;}
[tabindex="-1"]:focus{outline:0!important;}
h6{margin-bottom:.5rem;margin-top:0;}
b{font-weight:bolder;}
small{font-size:80%;}
a{-webkit-text-decoration-skip:objects;background-color:transparent;color:#047bf8;text-decoration:none;}
a:hover{color:#0356ad;text-decoration:underline;}
a:not([href]):not([tabindex]),a:not([href]):not([tabindex]):focus,a:not([href]):not([tabindex]):hover{color:inherit;text-decoration:none;}
a:not([href]):not([tabindex]):focus{outline:0;}
pre,samp{font-family:monospace,monospace;font-size:1em;}
pre{-ms-overflow-style:scrollbar;margin-bottom:1rem;margin-top:0;overflow:auto;}
svg:not(html){overflow:hidden;}
table{border-collapse:collapse;}
th{text-align:inherit;}
label{display:inline-block;margin-bottom:.5rem;}
button{border-radius:0;}
button:focus{outline:1px dotted;outline:5px auto -webkit-focus-ring-color;}
button,input,select{font-family:inherit;font-size:inherit;line-height:inherit;margin:0;}
button,input{overflow:visible;}
button,select{text-transform:none;}
button,html [type=button]{-webkit-appearance:button;}
[type=button]::-moz-focus-inner,button::-moz-focus-inner{border-style:none;padding:0;}
[type=search]{-webkit-appearance:none;outline-offset:-2px;}
h6{color:#334152;font-family:Avenir Next W01,Proxima Nova W01,Rubik,-apple-system,system-ui,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif;font-weight:500;line-height:1.2;margin-bottom:.5rem;}
h6{font-size:16px;font-size:1rem;}
small{font-size:80%;font-weight:400;}
pre,samp{font-family:SFMono-Regular,Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace;}
pre{color:#292b2c;display:block;font-size:87.5%;}
.container-fluid{margin-left:auto;margin-right:auto;padding-left:10px;padding-right:10px;width:100%;}
.row{display:flex;flex-wrap:wrap;margin-left:-10px;margin-right:-10px;}
.col-6,.col-md-6,.col-sm-3,.col-sm-12,.col-xxl-3{min-height:1px;padding-left:10px;padding-right:10px;position:relative;width:100%;}
.col-6{flex:0 0 50%;max-width:50%;}
.col-6{-webkit-box-flex:0;}
@media (min-width:576px){
.col-sm-3{flex:0 0 25%;max-width:25%;}
.col-sm-12{flex:0 0 100%;max-width:100%;}
}
@media (min-width:768px){
.col-md-6{flex:0 0 50%;max-width:50%;}
}
@media (min-width:1450px){
.col-xxl-3{flex:0 0 25%;max-width:25%;}
}
.table{background-color:transparent;margin-bottom:1rem;max-width:100%;width:100%;}
.table td,.table th{border-top:1px solid rgba(83,101,140,.33);padding:.75rem;vertical-align:top;}
.table thead th{border-bottom:2px solid rgba(83,101,140,.33);vertical-align:bottom;}
.table-striped tbody tr:nth-of-type(odd){background-color:rgba(94,130,152,.05);}
.table-responsive{-webkit-overflow-scrolling:touch;-ms-overflow-style:-ms-autohiding-scrollbar;display:block;overflow-x:auto;width:100%;}
.form-control{background-clip:padding-box;background-color:#fff;border:2px solid #dde2ec;border-radius:4px;color:#495057;display:block;font-size:14.4px;font-size:.9rem;line-height:1.5;padding:.375rem .75rem;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;width:100%;}
.form-control::-ms-expand{background-color:transparent;border:0;}
.form-control:focus{background-color:#fff;border-color:#047bf8;box-shadow:none;color:#495057;outline:0;}
.form-control:-ms-input-placeholder{color:#636c72;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";opacity:1;}
.form-control::-moz-placeholder{color:#636c72;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";opacity:1;}
.form-control::placeholder{color:#636c72;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";opacity:1;}
.form-control:disabled{background-color:#e9ecef;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";opacity:1;}
select.form-control:not([size]):not([multiple]){height:calc(2.1rem + 4px);}
.form-control-sm{border-radius:4px;font-size:12.8px;font-size:.8rem;line-height:1.5;padding:.25rem .5rem;}
select.form-control-sm:not([size]):not([multiple]){height:calc(1.7rem + 4px);}
.mt-5{margin-top:3rem!important;}
.pt-2{padding-top:.5rem!important;}
.pb-2{padding-bottom:.5rem!important;}
@media print{
*,:after,:before{box-shadow:none!important;text-shadow:none!important;}
a:not(.btn):not(.all-wrapper .fc-button){text-decoration:underline;}
pre{white-space:pre-wrap!important;}
pre{border:1px solid #999;page-break-inside:avoid;}
thead{display:table-header-group;}
tr{page-break-inside:avoid;}
body{min-width:992px!important;}
.table{border-collapse:collapse!important;}
.table td,.table th{background-color:#fff!important;}
}
.table th{font-weight:500;}
.table.table-lightfont td{font-weight:300;}
.table td,.table th{vertical-align:middle;}
.table thead th{border-bottom:1px solid #999;}
.table tfoot th{border-top:1px solid #999;}
.table tfoot th,.table thead th{border-top:none;font-size:10.08px;font-size:.63rem;text-transform:uppercase;}
@media (min-width:1100px){
.table-responsive{overflow:visible;}
}
.all-wrapper table.dataTable{border-collapse:collapse!important;}
.element-box .table:last-child{margin-bottom:0;}
label{margin-bottom:4px;}
button,input,select{font-family:Avenir Next W01,Proxima Nova W01,Rubik,-apple-system,system-ui,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif;font-weight:400;}
.form-control{font-family:Avenir Next W01,Proxima Nova W01,Rubik,-apple-system,system-ui,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif;}
.form-control:-ms-input-placeholder{color:rgba(0,0,0,.4);}
.form-control::-moz-placeholder{color:rgba(0,0,0,.4);}
.form-control::placeholder{color:rgba(0,0,0,.4);}
.form-control{font-weight:300;}
.dataTables_length select{width:50px;}
.dataTables_filter input,.dataTables_length select{display:inline-block;margin:0 5px;vertical-align:middle;}
.dataTables_filter input{width:130px;}
.dataTables_wrapper .row:first-child{border-bottom:1px solid rgba(0,0,0,.1);margin-bottom:1rem;margin-top:1rem;padding-bottom:.5rem;}
.dataTables_wrapper .row:last-child{border-top:1px solid rgba(0,0,0,.1);margin-bottom:1rem;margin-top:1rem;padding-top:.5rem;}
table.dataTable{border-collapse:separate!important;clear:both;margin-bottom:6px!important;margin-top:6px!important;max-width:none!important;}
table.dataTable td,table.dataTable th{box-sizing:content-box;}
div.dataTables_wrapper div.dataTables_length label{font-weight:400;text-align:left;white-space:nowrap;}
div.dataTables_wrapper div.dataTables_length select{display:inline-block;width:75px;}
div.dataTables_wrapper div.dataTables_filter{text-align:right;}
div.dataTables_wrapper div.dataTables_filter label{font-weight:400;text-align:left;white-space:nowrap;}
div.dataTables_wrapper div.dataTables_filter input{display:inline-block;margin-left:.5em;width:auto;}
table.dataTable thead>tr>th:active{outline:none;}
@media screen and (max-width:767px){
div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length{text-align:center;}
}
div.table-responsive>div.dataTables_wrapper>div.row{margin:0;}
div.table-responsive>div.dataTables_wrapper>div.row>div[class^=col-]:first-child{padding-left:0;}
div.table-responsive>div.dataTables_wrapper>div.row>div[class^=col-]:last-child{padding-right:0;}
.element-box{-webkit-animation-duration:1s;animation-duration:1s;-webkit-animation-name:fadeUp;animation-name:fadeUp;}
body{min-height:100%;overflow-x:hidden;padding:50px;position:relative;}
body:before{background:linear-gradient(to bottom right,#d7bbea,#65a8f1);bottom:0;content:"";left:0;position:absolute;right:0;top:0;z-index:-1;}
body.full-screen{padding:0;}
body.full-screen .all-wrapper{border-radius:0;max-width:none;}
b{font-weight:500;}
.all-wrapper{border-radius:6px;box-shadow:0 0 40px rgba(0,0,0,.1);margin:0 auto;max-width:1600px;min-height:100%;position:relative;}
.all-wrapper.solid-bg-all{background-color:#f2f4f8;}
.all-wrapper.solid-bg-all .content-w{background-image:none;}
body.menu-position-side .layout-w{display:flex;}
body.menu-position-side .content-w{-webkit-box-flex:1;border-radius:0 6px 6px 0;flex:1 1;}
.content-w{background-color:#f2f4f8;background-image:url(http://fz-project.test/img/bg-pattern.png);background-position:20px 50px;background-repeat:no-repeat;vertical-align:top;}
.content-box{flex:1 1;padding:2rem 2.5rem;vertical-align:top;}
.with-side-panel .content-i{-webkit-box-flex:1;display:flex;flex:1 1;}
.element-wrapper{padding-bottom:3rem;}
.element-wrapper .element-header{border-bottom:1px solid rgba(0,0,0,.05);margin-bottom:2rem;padding-bottom:1rem;position:relative;z-index:1;}
.element-wrapper .element-header:after{background-color:#047bf8;border-radius:0;bottom:-3px;content:"";display:block;height:4px;left:0;position:absolute;width:25px;}
.element-box{background-color:#fff;border-radius:6px;box-shadow:0 2px 4px rgba(126,142,177,.12);margin-bottom:1rem;padding:1.5rem 2rem;}
.el-tablo{display:block;}
.el-tablo .label{color:rgba(0,0,0,.4);display:block;font-size:10.08px;font-size:.63rem;letter-spacing:1px;text-transform:uppercase;}
.el-tablo .value{display:inline-block;font-family:Avenir Next W01,Proxima Nova W01,Rubik,-apple-system,system-ui,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif;font-size:38.88px;font-size:2.43rem;font-weight:500;letter-spacing:1px;line-height:1.2;vertical-align:middle;}
.el-tablo.trend-in-corner{position:relative;}
.el-tablo.centered{text-align:center;}
.el-tablo.centered{padding-left:10px;padding-right:10px;}
.el-tablo.smaller .value{font-size:27.36px;font-size:1.71rem;}
.el-tablo.smaller .label{font-size:10.08px;font-size:.63rem;letter-spacing:2px;}
a.el-tablo{color:#3e4b5b;display:block;text-decoration:none;}
a.el-tablo,a.el-tablo .label,a.el-tablo .value{transition:all .25s ease;}
a.el-tablo:hover{box-shadow:0 5px 12px rgba(126,142,177,.2);transform:translateY(-5px) scale(1.02);}
a.el-tablo:hover .value{color:#047bf8;transform:translateY(-3px);}
a.el-tablo:hover .label{color:#3395fc;}
a.el-tablo:hover.centered .value{transform:scale(1.1) translateY(-3px);}
a.el-tablo:hover .label{transform:translateY(-2px);}
@media (max-width:1650px){
body{padding:40px;}
.content-box{padding:2rem;}
.all-wrapper{max-width:100%;}
}
@media (max-width:1550px){
body{padding:20px;}
}
@media (min-width:1100px) and (max-width:1350px){
.content-box{padding:1.5rem;}
}
@media (max-width:1250px){
.element-box{padding:1rem 1.5rem;}
body{padding:0;}
.all-wrapper,.content-w{border-radius:0!important;overflow:hidden;}
}
@media (max-width:1150px){
.layout-w{transition:all .6s ease;}
.content-box:after{background:rgba(4,36,113,.6);bottom:0;content:"";display:block;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";left:0;opacity:0;position:absolute;right:0;top:0;transition:all .4s ease;visibility:hidden;z-index:10;}
.content-i,.content-i .content-box,.with-side-panel .content-i,.with-side-panel .content-i .content-box{display:block;}
}
@media (max-width:1024px){
.table td,.table th{padding:.7rem .5rem;}
.content-w{border-radius:0!important;}
}
@media (min-width:768px) and (max-width:1024px){
.element-wrapper{padding-bottom:2rem;}
.display-type{content:"tablet";}
.content-box{padding:1.5rem;}
.element-box{padding:1rem;}
.layout-w{flex-direction:row;}
}
@media (max-width:767px){
.layout-w{flex-direction:column;}
.el-tablo.smaller .value{font-size:1.26rem;margin-top:3px;}
.display-type{content:"phone";}
.element-box.el-tablo{text-align:center;}
.content-i,.content-w,.layout-w{display:block;}
.content-i .content-box{display:block;padding:15px;}
.element-wrapper{padding-bottom:1.5rem;}
.element-box{padding:1rem;}
table{max-width:100%;}
}
/*! CSS Used from: Embedded */
pre.sf-dump{display:block;white-space:pre;padding:5px;overflow:initial!important;}
pre.sf-dump:after{content:"";visibility:hidden;display:block;height:0;clear:both;}
pre.sf-dump span{display:inline;}
pre.sf-dump a{text-decoration:none;cursor:pointer;border:0;outline:none;color:inherit;}
pre.sf-dump .sf-dump-search-hidden{display:none!important;}
pre.sf-dump .sf-dump-search-wrapper{font-size:0;white-space:nowrap;margin-bottom:5px;display:flex;position:-webkit-sticky;position:sticky;top:5px;}
pre.sf-dump .sf-dump-search-wrapper > *{vertical-align:top;box-sizing:border-box;height:21px;font-weight:normal;border-radius:0;background:#FFF;color:#757575;border:1px solid #BBB;}
pre.sf-dump .sf-dump-search-wrapper > input.sf-dump-search-input{padding:3px;height:21px;font-size:12px;border-right:none;border-top-left-radius:3px;border-bottom-left-radius:3px;color:#000;min-width:15px;width:100%;}
pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next,pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-previous{background:#F2F2F2;outline:none;border-left:none;font-size:0;line-height:0;}
pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next{border-top-right-radius:3px;border-bottom-right-radius:3px;}
pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next > svg,pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-previous > svg{pointer-events:none;width:12px;height:12px;}
pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-count{display:inline-block;padding:0 5px;margin:0;border-left:none;line-height:21px;font-size:12px;}
pre.sf-dump{background-color:#18171B;color:#FF8400;line-height:1.2em;font:12px Menlo, Monaco, Consolas, monospace;word-wrap:break-word;white-space:pre-wrap;position:relative;z-index:99999;word-break:break-all;}
pre.sf-dump .sf-dump-str{font-weight:bold;color:#56DB3A;}
pre.sf-dump .sf-dump-note{color:#1299DA;}
pre.sf-dump .sf-dump-ref{color:#A0A0A0;}
pre.sf-dump .sf-dump-key{color:#56DB3A;}
/*! CSS Used keyframes */
@-webkit-keyframes fadeUp{0%{-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;transform:translateY(30px);}to{-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";opacity:1;transform:translateY(0);}}
@keyframes fadeUp{0%{-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;transform:translateY(30px);}to{-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";opacity:1;transform:translateY(0);}}
  </style>

@endsection

@section('contents')

<div class="row pt-2 pb-2">
	<div class="col-sm-12">
		<div class="element-wrapper">
			<h6 class="element-header">{{ now()->toDateString() }} Report</h6>
			<div class="row pt-2 pb-2">
			<div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Registered Customers</div>
              <div class="value">{{ $statistics['registered_customers_count'] }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Total Staffs</div>
              <div class="value">{{ $statistics['registered_sales_rep_count'] }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Total Supervisors</div>
              <div class="value">{{ $statistics['registered_supervisors_count'] }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Available Stock (Oil)</div>
              <div class="value">{{ $statistics['available_oil_stock_count'] }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Available Stock (Gallons)</div>
              <div class="value">{{ $statistics['available_gallon_stock_count'] }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Total Batches</div>
              <div class="value">{{ $statistics['price_batch_count'] }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Total Expenses</div>
              <div class="value">{{ to_naira($statistics['total_daily_expenses']) }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Total Transactions</div>
              <div class="value">{{ $statistics['total_purchase_orders_count'] }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Today's Transactions</div>
              <div class="value">{{ $statistics['total_daily_purchase_order_count'] }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Today's Purchase</div>
              <div class="value">{{ to_naira($statistics['total_daily_purchase_order_amount']) }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">This Month's Purchase</div>
              <div class="value">{{ $statistics['total_monthly_purchase_order_count'] }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">This Month's Purchase</div>
              <div class="value">{{ to_naira($statistics['total_monthly_purchase_order_amount']) }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Today's Profit</div>
              <div class="value">{{ to_naira($statistics['total_daily_profit']) }}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">This Month's Profit</div>
              <div class="value">{{ to_naira($statistics['total_monthly_profit']) }}</div>
            </a>
          </div>
			</div>
		</div>
	</div>
</div>


<div class="row mt-5">
	<div class="col-sm-12">
		<div class="element-wrapper">
			<h6 class="element-header">Purchase Orders</h6>
			<div class="element-box">
				<div class="table-responsive">
					<div id="dataTable1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

						<div class="row">
							<div class="col-sm-12">
								<table id="dataTable1" width="100%" class="table table-striped table-lightfont dataTable" role="grid"
									aria-describedby="dataTable1_info" style="width: 100%;">
									<thead>
										<tr role="row">
                      <th rowspan="1" colspan="1">S/N</th>
                      <th rowspan="1" colspan="1">Buyer</th>
                      <th rowspan="1" colspan="1">Stock Type</th>
                      <th rowspan="1" colspan="1">Qty</th>
                      <th rowspan="1" colspan="1">Swap / Qty / Value</th>
                      <th rowspan="1" colspan="1">Payment Type</th>
                      <th rowspan="1" colspan="1">Paid/Selling Price</th>
                      <th rowspan="1" colspan="1">Date</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
                      <th rowspan="1" colspan="1">S/N</th>
                      <th rowspan="1" colspan="1">Buyer</th>
                      <th rowspan="1" colspan="1">Stock Type</th>
                      <th rowspan="1" colspan="1">Qty</th>
                      <th rowspan="1" colspan="1">Swap / Qty / Value</th>
                      <th rowspan="1" colspan="1">Payment Type</th>
                      <th rowspan="1" colspan="1">Paid/Selling Price</th>
                      <th rowspan="1" colspan="1">Date</th>
										</tr>
									</tfoot>
									<tbody>

                    @foreach($statistics['orders'] as $order)
                      <tr role="row" class="odd">
                        <td class="sorting_1">{{ $order['id'] }}</td>
                        <td>{{ $order['buyer']['full_name'] }}</td>
                        <td>{{ $order['product_type']['product_type'] }}</td>
                        <td>{{ $order['purchased_quantity'] }}</td>
                        <td>{{ $order['swap_product_type']['product_type'] ?? 'N/A' }} / {{ $order['swap_quantity'] ?? 'N/A' }} / {{ to_naira($order['swap_value'] * $order['swap_quantity'] ?? 0) }}</td>
                        <td>{{ $order['payment_type'] === 'bank' ? $order['bank']['bank_name'] ?? 'N/A' . ' bank' : $order['payment_type']  }}</td>
                        <td>{{ to_naira($order['total_amount_paid']) }} / {{ to_naira($order['total_selling_price']) }}</td>
                        <td>{{ $order['created_at'] }}</td>
                      </tr>
                    @endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
