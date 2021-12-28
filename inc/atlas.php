<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin="" />

<style>
    #mymap {
        width: 100%;
        height: 40rem;
    }

    #pano {
        width: 100%;
        height: 40rem;
    }

    .info {
        padding: 6px 8px;
        font: 14px/16px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
    }

    .info h4 {
        margin: 0 0 5px;
        color: #777;
    }

    .legend {
        text-align: left;
        line-height: 18px;
        color: #555;
    }

    .legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.7;
    }
</style>

<div class="atlas">

    <div class="atlas-form">
        <div class="atlas-form--inline">
            <input class="form-control" id="zip" type="text" name="zip" placeholder="Enter Zip Code">
            <button type="submit" id="zip_code" onclick="getZipCodeLocation()" class="btn btn-white white">
                <i class="fa fa-search"></i>
                <span>Search</span>
            </button>
        </div>
    </div>

    <div class="atlas-map">
        <div id="mymap"></div>
    </div>

</div>

<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/gh/mattkingshott/iodine@3/dist/iodine.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>

<script type="text/javascript" src="<?= LINK ?>/assets/js/atlas/german-state.js"></script>

<script type="text/javascript" src="<?= LINK ?>/assets/js/atlas/data2.js"></script>
<script type="text/javascript" src="<?= LINK ?>/assets/js/atlas/germani_sub.js"></script>
<script type="text/javascript" src="<?= LINK ?>/assets/js/atlas/german_lower.js"></script>
<script type="text/javascript" src="<?= LINK ?>/assets/js/atlas/newdata.js"></script>
<script type="text/javascript" src="<?= LINK ?>/assets/js/atlas/settings.js"></script>