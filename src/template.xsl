<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html"/>

  <xsl:template match="/">
    <html>
      <head>
        <title>Sales</title>
      </head>
      <body>
        <div id="content">
          <table>
            <thead>
              <tr>
                <th>Seller</th>
                <th>Buyer</th>
                <th class="num">Amount</th>
                <th class="num">Price</th>
              </tr> 
            </thead>
            <tbody>
              <xsl:for-each select="result/entry[position() &lt; 6]">
                <tr>
                  <td><xsl:value-of select="seller_name"/></td>
                  <td><xsl:value-of select="buyer_name"/></td>
                  <td class="num"><xsl:value-of select="amount"/></td>
                  <td class="num"><xsl:value-of select="format-number(price, '##.00')"/></td>
                </tr>                
              </xsl:for-each>
            </tbody> 
        </table>
        </div>
        <div id="chart">
          <!-- Chart would go here -->
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
