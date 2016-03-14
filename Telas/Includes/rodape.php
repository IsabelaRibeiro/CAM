				</td>
			</tr>
			<?PHP
			if(!isset($_POST['JANELA']))
			{
			?>			
				<tr style="height: 3%">
					<td>
						<table class="rodape">
							<tr>
								<td width="10%">
								</td>
								<td width="90%" align="right" valign="middle" style="color: #FFFFFF; vertical-align: middle;">
									<?php echo "M&eacute;dium: " . $_SESSION['ssUsuario'];?>
								</td>
							</tr> 			
						</table>
					</td>
				</tr> 
			<?php }?>			
		</table> 
	</body>
</html>
<style>
.k-grid .k-state-selected { color: black; font-weight:bold;}
</style>

<script>
 $(".clCarregando").hide();
</script>