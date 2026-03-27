<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome     = mysqli_real_escape_string($conn, $_POST['nome']);
    $nick     = mysqli_real_escape_string($conn, $_POST['nickname']);
    $contatto = mysqli_real_escape_string($conn, $_POST['contatto']);

    mysqli_query($conn,
        "INSERT INTO clienti(nome, nickname, contatto)
         VALUES('$nome', '$nick', '$contatto')");
    echo "<div class='alert alert-success'>Cliente aggiunto!</div>";
}

$res = mysqli_query($conn, "SELECT * FROM clienti");


if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)$_POST['id'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    
    if (!empty($nome) && !empty($email)) {
        try {
            $stmt = $pdo->prepare("UPDATE utenti SET nome = :nome, email = :email WHERE id = :id");
            $stmt->execute(['id' => $id, 'nome' => $nome, 'email' => $email]);
            $messaggio = "Utente modificato con successo!";
        } catch (PDOException $e) {
            $errore = "Errore nella modifica: " . $e->getMessage();
        }
    } else {
        $errore = "Nome ed email sono obbligatori!";
    }
}


if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int)$_POST['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM utenti WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $messaggio = "Utente eliminato con successo!";
    } catch (PDOException $e) {
        $errore = "Errore nella cancellazione: " . $e->getMessage();
    }
}


header("Location: " . $_SERVER['PHP_SELF']);
exit;
?>

<?php if (isset($messaggio)): ?>
    <div class="message success"><?php echo htmlspecialchars($messaggio); ?></div>
<?php endif; ?>

<?php if (isset($errore)): ?>
    <div class="message error"><?php echo htmlspecialchars($errore); ?></div>
<?php endif; ?>

<h3>Clienti</h3>
<form method="POST">
    <input name="nome" class="form-control mb-2" placeholder="Nome cliente">
    <input name="nickname" class="form-control mb-2" placeholder="Es. famiglia, amico, clienteX">
    <input name="contatto" class="form-control mb-2" placeholder="Telefono o email (opzionale)">
    <button class="btn btn-primary">Aggiungi</button>
</form>

<div id="editForm" class="form-container hidden">
<h2>Modifica campi</h2>
    <form method="POST">
        <input type="hidden" name="action" value="update">
        <input type="hidden" id="edit-id" name="id">
        
        <div class="form-group">
            <label for="edit-nome">Nome:</label>
            <input type="text" id="edit-nome" name="nome" required>
        </div>
        
        <div class="form-group">
            <label for="edit-email">Email:</label>
            <input type="email" id="edit-email" name="email" required>
        </div>
        
        <button type="submit" class="btn-submit">
            Salva
        </button>
        
        <button type="button" class="btn-cancel" onclick="cancelEdit()">
            Annulla
        </button>
    </form>
</div>


<table class="table mt-3">
<tr><th>Nome</th><th>Nickname</th><th>Contatto</th></tr>
<?php while($c = mysqli_fetch_assoc($res)): ?>
<tr>
    <td><?= htmlspecialchars($c['nome']) ?></td>
    <td><?= htmlspecialchars($c['nickname']) ?></td>
    <?php if(isset($c['contatto'])): ?>
        <td><?= htmlspecialchars($c['contatto']) ?></td>
    <?php else: ?>
        <td>Null</td>
    <?php endif; ?>
    <th>
        <button 
            class="btn-edit" 
            onclick="showEditForm(
                <?php echo $utente['id']; ?>, 
                '<?php echo htmlspecialchars($utente['nome'], ENT_QUOTES); ?>', 
                '<?php echo htmlspecialchars($utente['email'], ENT_QUOTES); ?>'
            )">
            Modifica
        </button>
        <form method="POST" style="display: inline;">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo $utente['id']; ?>">
            <button type="submit" class="btn-delete" 
                    onclick="return confirm('Sei sicuro di voler eliminare questo utente?');">
                Cancella
            </button>
        </form>
    </th>
</tr>
<?php endwhile; ?>
</table>
<script>
    function showEditForm(id, nome, email) {

        document.getElementById('edit-id').value = id;
        document.getElementById('edit-nome').value = nome;
        document.getElementById('edit-email').value = email;
        
        document.getElementById('editForm').classList.remove('hidden');
        
        document.getElementById('editForm').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
    }
    

    function cancelEdit() {
        document.getElementById('editForm').classList.add('hidden');
    }
</script>

<?php require_once(__DIR__ . "/../common/footer.php"); ?>