    var path = require('path');
    const webpack = require('webpack');
    module.exports={
       	entry: ['./resources/assets/admin/polyfills.ts','./resources/assets/admin/main.ts'],
       	output:{
       		filename: './public/js/app.js',
        },
    	module:{
         exprContextCritical: false,
    		loaders:[{
    			test:/\.ts$/,
            include: path.resolve(__dirname,'resources/assets/admin'),
    			loader: 'ts-loader',
    		}]
    	},
    	resolve:{
    		extensions:[ ".webpack.js", ".web.js", ".ts", ".js"]
    	},
        watch: true,
   };
  