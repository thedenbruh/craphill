<?php
if($loggedIn) {
echo '<div class="secondary">
<div class="grid">
<div class="bottom-bar">
<ul>
<li>
<a href="/dashboard/" id="pHome">
Home
</a>
</li>
<li>
<a href="/settings/" id="pSettings">
Settings
</a>
</li>
<li>
 <a href="/customize/" id="pAvatar">
Avatar
</a>
</li>
<li>
<a href="/user/'. $userRow->{'id'}.'/" id="pProfile">
Profile
</a>
</li>
<li>
<a href="/client/" id="pDownload">
Download
</a>
</li>
<li>
<a href="/trades/" id="pTrades">
Trades
</a>
</li>
<li>
<a href="/sets/" id="pSets">
Sets
</a>
</li>
<li>
<a href="/currency/" id="pCurrency">
Currency
</a>
</li>
<li>
<a href="/blog/" id="pBlog">
Blog
</a>
</li>
</ul>
</div>
</div>
</div>
';
  }
?>