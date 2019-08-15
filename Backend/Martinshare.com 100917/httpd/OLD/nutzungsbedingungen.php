<?php
$noLogin = true;
$pageTitle = 'Nutzungsbedingungen';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
$user = new User();

include 'include/header.php';
echo '</head>';
echo '<body id="firstPage">';

if ($user->isLoggedIn())
{include 'include/navbar.php';}
?>

 <div class="container">
        <div class="row" >
           <div class="col-lg-12 text-center">

<h2>Nutzungsbedingungen</h2>
<p>INDEM SIE DIESE WEBSITE VERWENDEN, ERKLÄREN SIE SICH EINVERSTANDEN MIT DIESEN NUTZUNGSBEDINGUNGEN.<br>
VERWENDEN SIE DIESE WEBSITE NICHT, WENN SIE MIT DIESEN BEDINGUNGEN NICHT EINVERSTANDEN SIND.</p>

<br>
<h3>Anwendung dieser Nutzungsbedingungen</h3>
<p>Diese Nutzungsbedingungen regeln das Verhältnis zwischen Ihnen als Nutzer dieser Website und dem Betreiber dieser Website und der auf dieser Website verfügbaren Dienste.</p>

<br>
<h3>Änderungen der Nutzungsbedingungen</h3>
<p>martinshare.com behält sich vor, diese Nutzungsbedingungen jederzeit und ohne Nennung von Gründen zu ändern.</p>

<br>
<h3>Beschränkung auf persönliche, nicht kommerzielle Nutzung</h3>
<p>Die Dienste von "martinshare.com" werden ausschließlich zur persönlichen und nicht-kommerziellen Nutzung angeboten.<br>
Es ist Ihnen nicht gestattet, Informationen die Sie unter Zugriff auf "martinshare.com" erhalten haben, zu veröffentlichen, zu lizenzieren oder zu verkaufen.</p>

<br>
<h3>Regelung bezüglich der auf dieser Website verfügbaren Dokumente</h3>
<p>Sie sind berechtigt, Dokumente der angebotenen Dienste zu verwenden, sofern die Verwendung der Dokumente aus den Diensten ausschließlich zu<br>
informatorischen nicht-kommerziellen oder persönlichen Zwecken erfolgt und die Dokumente nicht auf anderen Netzwerk-Computern<br>
kopiert oder hinterlegt werden und nicht in anderen Medien publiziert werden. Mitschülern ist es gestattet, die Dokumente zur<br>
Verteilung im Unterrichtsraum herunterzuladen und zu vervielfältigen.<br>
Eine Verbreitung außerhalb der Unterrichtsräume bedarf der Zustimmung der Herausgeber von martinshare.com.</p>

<br>
<h3>Gewährleistung und Haftung</h3>
<p>Der Betreiber dieser Website übernimmt KEINERLEI HAFTUNG für die Richtigkeit der Informationen aus den Diensten.</p>

<br>
<h3>Cookies</h3>
<p>Diese Website verwendet Cookies.</p>

<br>
<h3>Verhaltenspflichten</h3>
<p>Ihre Nutzung der Dienste ist unter der Bedingung gestattet, dass Sie die Dienste nicht für Zwecke verwenden, die rechtswidrig sind<br>
oder gegen diese Nutzungsbedingungen und Hinweise verstoßen. Die Dienste dürfen nicht in einer Weise genutzt werden,<br>
die den Server oder die damit verbundenen Netzwerke schädigen, deaktivieren, überlasten oder beeinträchtigen könnten,<br>
oder die die Nutzung der Dienste durch Dritte beeinträchtigen könnten.</p>

<br>
<h3>Links auf Seiten Dritter</h3>
<p>Die auf der Website enthaltenen Links ermöglichen es Ihnen, die Website zu verlassen und zu anderen Websites zu springen.<br>
martinshare.com ist für Inhalte, Links, Änderungen oder Updates fremder Websites nicht verantwortlich. </p>

</div>
</div>
</div>
<?php
if ($user->isLoggedIn())
{include 'include/footer.php';}
?>

</body>
</html>