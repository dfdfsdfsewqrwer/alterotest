<h1>Submit application</h1>

<?php if ( isset( $this->flash[ 'error' ] ) ): ?>
    <div class="error"><?= $this->flash[ 'error' ] ?></div>
<?php endif ?>

<article class="info">
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tincidunt elit ac nisi iaculis laoreet. Nulla facilisi. Donec velit urna, blandit sit amet velit quis, scelerisque fringilla ligula. Sed sed leo sed lorem tincidunt mattis sed eu nulla. Aenean augue turpis, fermentum a facilisis non, consequat sed purus. Sed vulputate, felis elementum commodo convallis, turpis odio imperdiet quam, sed blandit turpis odio nec orci. Cras semper lectus vitae velit varius vehicula vel eu risus. Sed posuere vel ipsum nec porttitor. Quisque porta nunc sed mauris maximus, pharetra convallis ante finibus.</p>
</article>


<form action="/apply" method="post" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= $this->MakeCSRF() ?>"/>
    <table>
        <tbody>
            <tr>
                <td>E-Mail:</td><td><input type="email" name="email" value="" required/></td>
            </tr>
            <tr>
                <td>Amount:</td><td><input type="number" name="amount" value="" step="0.01" placeholder="0.00" required/></td>
            </tr>
            <tr>
                <td><input type="submit"/></td>
                <td><a href="/">Cancel</a></td>
            </tr>
        </tbody>
    </table>
    
</form>