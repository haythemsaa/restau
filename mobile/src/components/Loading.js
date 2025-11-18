/**
 * Loading Component
 */

import React from 'react';
import {View, ActivityIndicator, Text, StyleSheet} from 'react-native';
import {colors, typography, spacing} from '../utils/theme';

const Loading = ({text = 'Chargement...', size = 'large', color = colors.primary}) => {
  return (
    <View style={styles.container}>
      <ActivityIndicator size={size} color={color} />
      {text && <Text style={styles.text}>{text}</Text>}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: colors.background,
    padding: spacing.xl,
  },
  text: {
    marginTop: spacing.base,
    fontSize: typography.fontSize.base,
    color: colors.textLight,
    textAlign: 'center',
  },
});

export default Loading;
