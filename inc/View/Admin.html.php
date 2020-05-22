<h1>Admin panel</h1>

<?php foreach( [ 'success', 'error' ] as $message ): ?>
    <?php if ( isset( $this->flash[ $message ] ) ): ?>
        <div class="<?= $message ?>"><?= $this->flash[ $message ] ?></div>
    <?php endif ?>
<?php endforeach; ?>

<form class="admin" method="post" action="/admin" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= $this->MakeCSRF() ?>"/>
    <table>
        <thead>
            <tr>
                <th>Application E-Mail</th>
                <th>Application Amount</th>
                <th>Partner</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $deals as $deal ):?>
            <tr>
                <td><?= $deal->application->email ?></td>
                <td><?= number_format( $deal->application->amount, 2 ) ?></td>
                <td><?= $deal->partner ?></td>
                <td>
                    <select name="deals[<?= $deal->id ?>]">
                        <?php foreach( [ 'ask', 'offer' ] as $status ): ?>
                        <option value="<?= $status ?>" <?php if ( $status == $deal->status ): ?> selected <?php endif ?>><?= $status ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr>


            <?php endforeach ?>

        </tbody>
    </table>
    <input type="submit" value="UPDATE"/>
    <a href="/">Return to home</a>
</form>