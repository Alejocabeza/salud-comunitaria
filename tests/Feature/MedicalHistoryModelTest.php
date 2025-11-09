<?php

use App\Models\MedicalHistory;

it('casts attachments to array', function () {
    $mh = MedicalHistory::factory()->make([
        'attachments' => [['path' => 'a.pdf']],
    ]);

    expect($mh->attachments)->toBeArray();
});
